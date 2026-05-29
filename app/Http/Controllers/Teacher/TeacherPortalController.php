<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Material;
use App\Models\MusicClass;
use App\Models\Student;
use App\Models\StudentProgress;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeacherPortalController extends Controller
{
    private function hasAssignmentStatusColumn(): bool
    {
        return Schema::hasColumn('classes', 'assignment_status');
    }

    private function teacherAcceptedClassesQuery($teacherId)
    {
        return MusicClass::query()->whereHas('teachers', function ($q) use ($teacherId) {
            $q->where('teachers.id', $teacherId);
        })->orWhere('teacher_id', $teacherId); // Keep orWhere for backward compatibility during migration
    }

    private function teacherFromUser($userId): Teacher
    {
        return Teacher::firstOrCreate(
            ['user_id' => $userId],
            ['name' => 'Teacher User '.$userId, 'instrument' => 'General', 'is_active' => true]
        );
    }

public function dashboard(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $todayDay = now()->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.
        $today = now()->toDateString();
        
        // Map Laravel dayOfWeek to our day names
        $dayMap = [
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
        ];
        $todayDayName = $dayMap[$todayDay];
        
        // Get today's sessions (booked only)
        $todaySchedules = \App\Models\ScheduleSession::query()
            ->where('teacher_id', $teacher->id)
            ->where('session_date', $today)
            ->where('status', 'booked')
            ->with([
                'musicClass:id,name',
                'student:id,name,address'
            ])
            ->orderBy('time')
            ->get();
        
        // Count completed (attended) lessons for today
        $completedCount = Attendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('created_at', $today)
            ->where('status', 'present')
            ->count();

        $acceptedClasses = $this->teacherAcceptedClassesQuery($teacher->id)->orderBy('name')->get(['id', 'name', 'schedule']);
        $classIds = $acceptedClasses->pluck('id');

        return view('portal.teacher.dashboard', [
            'teacher' => $teacher,
            'todaySchedules' => $todaySchedules,
            'completedCount' => $completedCount,
            'classCount' => $classIds->count(),
            'studentCount' => Student::whereHas('schedules', fn ($q) => $q->where('teacher_id', $teacher->id))->count(),
            'attendanceCount' => Attendance::where('teacher_id', $teacher->id)->whereDate('created_at', now()->toDateString())->count() + TeacherAttendance::where('teacher_id', $teacher->id)->whereDate('attendance_date', now()->toDateString())->count(),
            'progressCount' => StudentProgress::where('teacher_id', $teacher->id)->count(),
            'assignedClasses' => $acceptedClasses,
            'latestProgress' => StudentProgress::with('student:id,name')->where('teacher_id', $teacher->id)->latest()->take(5)->get(),
            'hasTeacherAttendanceToday' => TeacherAttendance::query()->where('teacher_id', $teacher->id)->whereDate('attendance_date', now()->toDateString())->exists(),
            'pendingRescheduleRequests' => \App\Models\RescheduleRequest::with(['student:id,name', 'oldSchedule', 'newSchedule', 'oldSession'])
                ->whereHas('oldSchedule', fn($q) => $q->where('teacher_id', $teacher->id))
                ->where('status', 'pending')
                ->latest()
                ->get(),
        ]);
    }

    public function attendance(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $classes = $this->teacherAcceptedClassesQuery($teacher->id)
            ->with(['students:id,name'])
            ->get();
        $today = now()->toDateString();
        $hasTeacherAttendanceToday = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('attendance_date', $today)
            ->exists();
        $hasAssignedClasses = $classes->isNotEmpty();
        $classStudents = $classes->mapWithKeys(function (MusicClass $class) {
            return [
                $class->id => $class->students
                    ->sortBy('name')
                    ->map(fn (Student $student) => [
                        'id' => $student->id,
                        'name' => $student->name,
                    ])
                    ->values(),
            ];
        });

        return view('portal.teacher.attendance', [
            'teacher' => $teacher,
            'classOptions' => $classes,
            'hasAssignedClasses' => $hasAssignedClasses,
            'classStudents' => $classStudents,
            'records' => Attendance::with(['class', 'student'])->where('teacher_id', $teacher->id)->latest('created_at')->take(20)->get(),
            'teacherRecords' => TeacherAttendance::with('teacher')->where('teacher_id', $teacher->id)->latest('attendance_date')->take(20)->get(),
            'hasTeacherAttendanceToday' => $hasTeacherAttendanceToday,
        ]);
    }

    public function storeAttendance(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        $teacherClassIds = $this->teacherAcceptedClassesQuery($teacher->id)->pluck('id');

        if ($teacherClassIds->isEmpty()) {
            return back()->withErrors([
                'class_id' => 'Belum ada kelas yang di-assign ke guru ini. Hubungi Admin/Super Admin untuk assign kelas.',
            ])->withInput();
        }

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'student_id' => ['required', 'exists:students,id'],
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,late'],
            'note' => ['nullable', 'string'],
        ]);

        $class = MusicClass::query()
            ->where('id', $data['class_id'])
            ->where(fn($q) => $q->where('teacher_id', $teacher->id)
                ->orWhereHas('teachers', fn($t) => $t->where('teachers.id', $teacher->id))
            )
            ->first();

        if ($this->hasAssignmentStatusColumn()) {
            $class = MusicClass::query()
                ->where('id', $data['class_id'])
                ->where(fn($q) => $q->where('teacher_id', $teacher->id)
                    ->orWhereHas('teachers', fn($t) => $t->where('teachers.id', $teacher->id))
                )
                ->where('assignment_status', 'accepted')
                ->first();
        }

        if (! $class) {
            return back()->withErrors([
                'class_id' => 'Kelas yang dipilih tidak terdaftar pada guru ini.',
            ])->withInput();
        }

        $student = Student::query()->find($data['student_id']);
        if (! $student || ! $class->students()->where('students.id', $student->id)->exists()) {
            return back()->withErrors([
                'student_id' => 'Siswa tidak terdaftar pada kelas yang dipilih.',
            ])->withInput();
        }

        $teacherHasAttendance = TeacherAttendance::query()
            ->where('teacher_id', $teacher->id)
            ->whereDate('attendance_date', $data['attendance_date'])
            ->exists();

        if (! $teacherHasAttendance) {
            return back()->withErrors([
                'attendance_date' => 'Guru wajib absen terlebih dahulu pada tanggal tersebut sebelum mengisi absen siswa.',
            ])->withInput();
        }

        Attendance::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'created_at' => $data['attendance_date'],
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
        ]);

        return back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function storeTeacherAttendance(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $data = $request->validate([
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,late'],
            'location_text' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'note' => ['nullable', 'string'],
            'image_proof' => ['nullable', 'string'], // base64 from camera
        ]);

        // Handle base64 Image Proof
        $imagePath = null;
        if (! empty($data['image_proof'])) {
            $imageData = $data['image_proof'];
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);

                if ($imageData !== false) {
                    $fileName = 'teacher_att_' . $teacher->id . '_' . time() . '.' . $type;
                    $path = 'attendances/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($path, $imageData);
                    $imagePath = $path;
                }
            }
        }

        TeacherAttendance::create([
            'teacher_id' => $teacher->id,
            'attendance_date' => $data['attendance_date'],
            'status' => $data['status'],
            'location_text' => $data['location_text'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'note' => $data['note'],
            'image_path' => $imagePath,
        ]);

        return back()->with('success', 'Absensi guru berhasil disimpan dengan bukti foto dan lokasi.');
    }

    public function progress(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        
        // Ambil kelas resmi
        $classes = $this->teacherAcceptedClassesQuery($teacher->id)
            ->with(['students:id,name'])
            ->orderBy('name')
            ->get();

        // Ambil siswa dari kelas resmi
        $studentsFromClasses = $classes->flatMap(fn (MusicClass $class) => $class->students);

        // Ambil siswa dari Jadwal (Schedule)
        $studentsFromSchedule = Student::whereHas('scheduleSessions', fn($q) => $q->where('teacher_id', $teacher->id))
            ->select(['id', 'name'])
            ->get();

        // Gabungkan keduanya
        $students = $studentsFromClasses->concat($studentsFromSchedule)
            ->unique('id')
            ->sortBy('name')
            ->values();

        $selectedStudentId = $request->integer('student_id');
        if ($selectedStudentId) {
            $allStudentIds = $students->pluck('id')->map(fn($id) => (int)$id)->toArray();
            if (!in_array((int)$selectedStudentId, $allStudentIds)) {
                abort(403, 'Siswa bukan milik teacher yang sedang login.');
            }
        }

        $selectedClassId = null;
        if ($selectedStudentId) {
            $selectedClass = $classes->first(function (MusicClass $class) use ($selectedStudentId) {
                return $class->students->contains(fn (Student $student) => (int) $student->id === $selectedStudentId);
            });

            $selectedClassId = $selectedClass?->id;
        }

        $classStudents = $classes->mapWithKeys(function (MusicClass $class) {
            return [
                $class->id => $class->students
                    ->sortBy('name')
                    ->map(fn (Student $student) => [
                        'id' => $student->id,
                        'name' => $student->name,
                    ])
                    ->values(),
            ];
        });

        return view('portal.teacher.progress', [
            'teacher' => $teacher,
            'classes' => $classes,
            'students' => $students,
            'records' => StudentProgress::where('teacher_id', $teacher->id)->latest()->take(20)->get(),
            'classStudents' => $classStudents,
            'selectedStudentId' => $selectedStudentId,
            'selectedClassId' => $selectedClassId,
        ]);
    }

    public function progressForStudent(Request $request, $student_id): View
    {
        $request->merge(['student_id' => $student_id]);

        return $this->progress($request);
    }

    public function storeProgress(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'student_id' => ['required', 'exists:students,id'],
            'topic' => ['nullable', 'string', 'max:120'],
            'note' => ['nullable', 'string'],
            'score' => ['nullable', 'string', 'max:20'],
            'recorded_at' => ['nullable', 'date'],
        ]);

        $class = $this->teacherAcceptedClassesQuery($teacher->id)
            ->where('id', $data['class_id'])
            ->first();

        if (! $class) {
            return back()->withErrors([
                'class_id' => 'Kelas tidak terdaftar untuk teacher yang sedang login.',
            ])->withInput();
        }

        $studentInClass = $class->students()
            ->where('students.id', $data['student_id'])
            ->exists();

        if (! $studentInClass) {
            return back()->withErrors([
                'student_id' => 'Siswa tidak terdaftar pada kelas teacher ini.',
            ])->withInput();
        }

        $data['teacher_id'] = $teacher->id;
        StudentProgress::create($data);

        return back()->with('success', 'Progress siswa berhasil disimpan.');
    }

    public function materials(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        return view('portal.teacher.materials', [
            'teacher' => $teacher,
            'classes' => $this->teacherAcceptedClassesQuery($teacher->id)->get(),
            'materials' => Material::where('teacher_id', $teacher->id)->latest()->get(),
        ]);
    }

public function schedule(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $schedules = \App\Models\ScheduleSession::query()
            ->where('teacher_id', $teacher->id)
            ->whereIn('status', ['booked', 'rescheduled', 'completed'])
            ->with(['musicClass', 'student.user', 'attendance', 'rescheduleRequests'])
            ->orderBy('session_date')
            ->orderBy('time')
            ->get();

        return view('portal.teacher.schedule', [
            'teacher' => $teacher,
            'schedules' => $schedules,
        ]);
    }

    public function storeScheduleAttendance(Request $request)
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $validated = $request->validate([
            'session_id' => 'required|exists:schedule_sessions,id',
            'status' => 'required|in:present,absent,reschedule',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'note' => 'nullable|string',
            'attendance_image' => 'nullable|string', // base64
        ]);

        $session = \App\Models\ScheduleSession::where('id', $validated['session_id'])
            ->where('teacher_id', $teacher->id)
            ->where('status', 'booked')
            ->firstOrFail();

        if (now()->addMinutes(30)->lt(\Carbon\Carbon::parse($session->session_date->format('Y-m-d') . ' ' . $session->time))) {
            return back()->with('error', 'Belum bisa absen. Absensi baru dibuka 30 menit sebelum jadwal kelas.');
        }

        if ($session->attendance()->exists()) {
            return back()->with('error', 'Attendance already exists for this session.');
        }

        // Handle Image Upload
        $imagePath = null;
        if (!empty($validated['attendance_image'])) {
            $imageData = $validated['attendance_image'];
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);

                if ($imageData !== false) {
                    $fileName = 'attendance_' . $session->id . '_' . time() . '.' . $type;
                    $path = 'attendances/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($path, $imageData);
                    $imagePath = $path;
                }
            }
        }

        $attendance = $session->attendance()->create([
            'teacher_id' => $teacher->id,
            'student_id' => $session->student_id,
            'status' => $validated['status'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'note' => $validated['note'],
            'session_id' => $session->id,
            'schedule_id' => $session->schedule_id,
            'image_path' => $imagePath,
        ]);

        $session->update(['status' => 'completed']);

        // Send Fonnte Notification for completed attendance
        try {
            $fonnteToken = env('FONNTE_TOKEN');
            $groupFull = '120363425095640755@g.us'; // Grup laporan lengkap

            if ($fonnteToken) {
                $teacherName = $teacher->user->name ?? $teacher->name;
                $teacherPhone = $teacher->phone ?? null;
                $target = $groupFull;
                
                if ($teacherPhone) {
                    $formattedPhone = preg_replace('/[^0-9]/', '', $teacherPhone);
                    if (str_starts_with($formattedPhone, '0')) {
                        $formattedPhone = '62' . substr($formattedPhone, 1);
                    }
                    $target .= ',' . $formattedPhone;
                }


                $studentName = $session->student->user->name ?? ($session->student->name ?? 'Siswa');
                $className = $session->musicClass->name ?? '-';
                $timeFormatted = \Carbon\Carbon::parse($session->time)->format('H:i');
                $statusText = ucfirst($validated['status']);
                $absenTime = $attendance->created_at->timezone('Asia/Jakarta')->format('H:i') . ' WIB';

                $mapsLink = ($validated['latitude'] && $validated['longitude']) 
                    ? "https://www.google.com/maps?q={$validated['latitude']},{$validated['longitude']}" 
                    : "Tidak ada lokasi";

                // 1. Pesan Lengkap (Full)
                $messageFull = "✅ *LAPORAN KEHADIRAN KELAS LENGKAP (ROFC MUSIC)*\n\n";
                $messageFull .= "Terima kasih Coach *{$teacherName}*!\n";
                $messageFull .= "Kehadiran untuk kelas berikut telah berhasil dicatat:\n\n";
                $messageFull .= "Siswa: *{$studentName}*\n";
                $messageFull .= "Kelas: *{$className}*\n";
                $messageFull .= "Jam Sesi: *{$timeFormatted} WIB*\n";
                $messageFull .= "Waktu Absen: *{$absenTime}*\n";
                $messageFull .= "Status Kehadiran: *{$statusText}*\n";
                $messageFull .= "Catatan: " . ($validated['note'] ?: '-') . "\n";
                $messageFull .= "📍 Lokasi: {$mapsLink}\n\n";
                $messageFull .= "_Laporan ini tercatat secara otomatis di sistem. Semangat untuk kelas selanjutnya! _";

                $payloadFull = [
                    'target' => $target,
                    'message' => $messageFull,
                    'countryCode' => '62',
                ];



                if ($imagePath) {
                    $payloadFull['url'] = url('storage/' . $imagePath);
                }

                \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => $fonnteToken,
                ])->post('https://api.fonnte.com/send', $payloadFull);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte Attendance Notification Error: ' . $e->getMessage());
        }

        return back()->with('success', 'Attendance recorded successfully.');
    }

    public function myClasses(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $classes = $this->teacherAcceptedClassesQuery($teacher->id)
            ->with([
                'schedules' => function($query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id)->with('student:id,name,is_active,end_date');
                }
            ])
            ->withCount(['schedules as students_count' => function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id)->select(\DB::raw('count(distinct student_id)'))->whereNotNull('student_id');
            }])
            ->withCount(['schedules' => function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id)->whereNotNull('student_id');
            }])
            ->orderBy('name')
            ->get();

        $totalClasses = $classes->count();
        $totalStudents = $classes->pluck('schedules')->flatten()->pluck('student_id')->filter()->unique()->count();
        
        $thisMonthSessions = \App\Models\Attendance::where('teacher_id', $teacher->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('portal.teacher.my-classes', [
            'teacher' => $teacher,
            'classes' => $classes,
            'stats' => [
                'totalClasses' => $totalClasses,
                'totalStudents' => $totalStudents,
                'thisMonthSessions' => $thisMonthSessions,
            ]
        ]);
    }

    public function respondSchedule(Request $request, MusicClass $class): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        if (! $this->hasAssignmentStatusColumn()) {
            return back()->withErrors([
                'schedule' => 'Fitur respon jadwal belum aktif karena database belum di-migrate. Jalankan php artisan migrate terlebih dahulu.',
            ]);
        }

        if ((int) $class->teacher_id !== (int) $teacher->id && !$class->teachers->contains($teacher->id)) {
            abort(403, 'Jadwal ini bukan untuk teacher yang sedang login.');
        }

        $data = $request->validate([
            'action' => ['required', 'in:accepted,rejected'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $class->update([
            'assignment_status' => $data['action'],
            'assignment_note' => $data['note'] ?? null,
            'responded_at' => now(),
        ]);

        return back()->with('success', 'Respon jadwal berhasil disimpan.');
    }

    public function storeMaterial(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $data = $request->validate([
            'class_id' => ['nullable', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $data['teacher_id'] = $teacher->id;
        $data['file_path'] = $request->file('file')->store('materials', 'public');
        unset($data['file']);

        Material::create($data);

        return back()->with('success', 'Materi berhasil diupload.');
    }
    public function profile(Request $request): View
    {
        $teacher = $this->teacherFromUser($request->user()->id);
        
        return view('portal.teacher.profile', [
            'teacher' => $teacher,
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $teacher = $this->teacherFromUser($user->id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        // Update User info
        $user->update([
            'name' => $data['name'],
        ]);

        // Update Teacher info
        $teacherData = [
            'name' => $data['name'],
            'phone' => $data['phone'],
        ];

        if ($request->hasFile('photo')) {
            if ($teacher->photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($teacher->photo_path);
            }
            $teacherData['photo_path'] = $request->file('photo')->store('teachers/photos', 'public');
        }

        $teacher->update($teacherData);

        // Update Password if provided
        if (!empty($data['new_password'])) {
            $user->update([
                'password' => \Illuminate\Support\Facades\Hash::make($data['new_password']),
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function availableSlots(Request $request): \Illuminate\Http\JsonResponse
    {
        $teacherId = $request->query('teacher_id');
        $classId = $request->query('class_id');
        
        if (!$teacherId || !$classId) return response()->json(['grouped' => []]);

        $slots = \App\Models\Schedule::query()
            ->where('teacher_id', $teacherId)
            ->where('class_id', $classId)
            ->where('status', 'available')
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('time')
            ->get(['id', 'day', 'time']);

        $dayMap = [
            'Senin' => \Carbon\Carbon::MONDAY,
            'Selasa' => \Carbon\Carbon::TUESDAY,
            'Rabu' => \Carbon\Carbon::WEDNESDAY,
            'Kamis' => \Carbon\Carbon::THURSDAY,
            'Jumat' => \Carbon\Carbon::FRIDAY,
            'Sabtu' => \Carbon\Carbon::SATURDAY,
            'Minggu' => \Carbon\Carbon::SUNDAY,
        ];

        $grouped = $slots->groupBy('day')->map(function($daySlots, $dayName) use ($dayMap) {
            $carbonDay = $dayMap[$dayName] ?? \Carbon\Carbon::MONDAY;
            
            $now = \Carbon\Carbon::now();
            if ($now->dayOfWeek === $carbonDay) {
                $date = $now;
            } else {
                $date = $now->copy()->next($carbonDay);
            }

            return $daySlots->map(fn($s) => [
                'id' => $s->id,
                'day' => $s->day,
                'date_label' => $date->translatedFormat('l, d M Y'),
                'time' => substr((string)$s->time, 0, 5),
            ]);
        });

        return response()->json(['grouped' => $grouped]);
    }

    public function requestReschedule(Request $request): RedirectResponse
    {
        $teacher = $this->teacherFromUser($request->user()->id);

        $validated = $request->validate([
            'old_session_id' => ['required', 'exists:schedule_sessions,id'],
            'new_schedule_id' => ['required', 'exists:schedules,id'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $oldSession = \App\Models\ScheduleSession::where('id', $validated['old_session_id'])
            ->where('teacher_id', $teacher->id)
            ->where('status', 'booked')
            ->firstOrFail();

        $exists = \App\Models\RescheduleRequest::where('old_session_id', $validated['old_session_id'])
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Sesi ini sudah memiliki permintaan reschedule yang sedang diproses.');
        }

        \App\Models\RescheduleRequest::create([
            'student_id' => $oldSession->student_id,
            'old_schedule_id' => $oldSession->schedule_id,
            'old_session_id' => $oldSession->id,
            'new_schedule_id' => $validated['new_schedule_id'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Send Fonnte Notification for new reschedule request
        try {
            $fonnteToken = env('FONNTE_TOKEN');
            $groupId = '120363425095640755@g.us'; // Specific group ID

            if ($fonnteToken) {
                $teacherPhone = $teacher->phone ?? null;
                $target = $groupId;
                
                if ($teacherPhone) {
                    $formattedPhone = preg_replace('/[^0-9]/', '', $teacherPhone);
                    if (str_starts_with($formattedPhone, '0')) {
                        $formattedPhone = '62' . substr($formattedPhone, 1);
                    }
                    $target .= ',' . $formattedPhone;
                }

                $studentName = $oldSession->student->name ?? 'Siswa';
                $className = $oldSession->musicClass->name ?? 'Kelas';
                $oldTime = $oldSession->session_date->format('d M Y') . ' ' . \Carbon\Carbon::parse($oldSession->time)->format('H:i');
                
                $newTimeText = "Dorong Mundur 1 Minggu";
                if ($validated['new_schedule_id'] != $oldSession->schedule_id) {
                    $newSlot = \App\Models\Schedule::find($validated['new_schedule_id']);
                    if ($newSlot) {
                        $newTimeText = $newSlot->day_of_week . ' ' . \Carbon\Carbon::parse($newSlot->start_time)->format('H:i');
                    }
                }

                $message = "*PERMINTAAN RESCHEDULE BARU*\n\n";
                $message .= "Siswa: *$studentName*\n";
                $message .= "Kelas: *$className*\n";
                $message .= "Jadwal Lama: *$oldTime*\n";
                $message .= "Jadwal Baru: *$newTimeText*\n";
                $message .= "Alasan: " . ($validated['reason'] ?: '-') . "\n\n";
                $message .= "Diajukan oleh: *Guru ({$teacher->name})*\n";
                $message .= "Silakan cek dashboard Super Admin untuk memproses permintaan ini.";

                \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => $fonnteToken,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte Reschedule Request Notification Error: ' . $e->getMessage());
        }

        return back()->with('success', 'Permintaan reschedule telah dikirim dan menunggu persetujuan admin.');
    }
}

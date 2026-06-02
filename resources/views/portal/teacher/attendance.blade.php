@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index')],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index')],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index')],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index')],
        ['label' => 'Materials', 'url' => route('teacher.materials.index')],
        ['label' => 'Profile', 'url' => route('teacher.profile.index')],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp

@extends('portal.layouts.app')

@section('title', 'Attendance')
@section('page-title', 'Attendance')
@section('page-subtitle', 'Lakukan absensi harian dan catat kehadiran siswa.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-2">
    {{-- Teacher Self-Attendance Card --}}
    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div>
                <h3 class="text-base font-bold text-slate-900 leading-none">Absen Guru</h3>
                <p class="text-[11px] font-medium text-slate-400 mt-1">Self-attendance dengan GPS & Foto</p>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $hasTeacherAttendanceToday ? 'bg-green-50 text-green-700 border-green-100' : 'bg-amber-50 text-amber-700 border-amber-100' }} text-[9px] font-bold border">
                {{ $hasTeacherAttendanceToday ? 'SUDAH ABSEN' : 'BELUM ABSEN' }}
            </span>
        </div>

        <form class="p-6 flex-1 flex flex-col gap-5" method="POST" action="{{ route('teacher.attendance.teacher.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Guru</label>
                    <input type="text" value="{{ $teacher->name }}" readonly class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal</label>
                    <input type="date" name="attendance_date" value="{{ now()->format('Y-m-d') }}" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                </div>
            </div>

            <div class="relative group">
                <div class="aspect-[4/3] rounded-2xl bg-slate-900 overflow-hidden border-2 border-slate-100 shadow-inner relative">
                    <video id="video" autoplay playsinline class="h-full w-full object-cover"></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    <img id="photo-preview" class="hidden h-full w-full object-cover">
                    
                    <div class="absolute bottom-4 left-4 right-4 flex flex-col gap-1 pointer-events-none">
                        <div class="inline-flex self-start items-center gap-1.5 bg-slate-900/60 backdrop-blur-md border border-white/10 rounded-full px-2.5 py-1 text-[9px] font-bold text-white uppercase tracking-wider" id="cam-time">
                            {{ now()->format('d/m/Y H:i:s') }}
                        </div>
                        <div class="inline-flex self-start items-center gap-1.5 bg-blue-600/80 backdrop-blur-md border border-white/10 rounded-full px-2.5 py-1 text-[9px] font-bold text-white uppercase tracking-wider" id="cam-loc">
                            Waiting for GPS...
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2 justify-center">
                    <button type="button" id="btn-start-cam" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-[10px] font-bold hover:bg-slate-800 transition-all active:scale-95">
                        <i data-lucide="camera" class="w-3.5 h-3.5"></i> AKTIFKAN KAMERA
                    </button>
                    <button type="button" id="btn-capture" class="hidden inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-[10px] font-bold hover:bg-blue-700 transition-all active:scale-95 shadow-lg shadow-blue-100">
                        <i data-lucide="aperture" class="w-3.5 h-3.5"></i> AMBIL FOTO PROOF
                    </button>
                    <button type="button" id="btn-retake" class="hidden inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-50 text-red-600 border border-red-100 text-[10px] font-bold hover:bg-red-100 transition-all">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> ULANGI
                    </button>
                </div>
                <input type="hidden" name="image_proof" id="image-proof-data">
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Live Location</label>
                    <div class="flex gap-2">
                        <input type="text" id="teacher-location-text" name="location_text" placeholder="Klik tombol untuk ambil lokasi" readonly required class="flex-1 bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500">
                        <button type="button" id="btn-get-location" class="h-10 w-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 transition-all">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <input type="hidden" id="teacher-latitude" name="latitude">
                    <input type="hidden" id="teacher-longitude" name="longitude">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status</label>
                        <select name="status" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                            <option value="present">Hadir (Present)</option>
                            <option value="absent">Izin / Sakit (Absent)</option>
                            <option value="late">Terlambat (Late)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Note</label>
                        <input type="text" name="note" placeholder="Catatan singkat..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-blue-600 text-white text-xs font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-2 mt-auto" id="btn-submit-attendance">
                <i data-lucide="check-circle" class="w-4 h-4"></i> SIMPAN ABSEN GURU
            </button>
        </form>
    </section>

    {{-- Student Attendance Card --}}
    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div>
                <h3 class="text-base font-bold text-slate-900 leading-none">Absen Siswa</h3>
                <p class="text-[11px] font-medium text-slate-400 mt-1">Catat kehadiran siswa di kelas Anda</p>
            </div>
            <i data-lucide="users" class="w-4 h-4 text-slate-300"></i>
        </div>

        @if($hasTeacherAttendanceToday)
            @if($hasAssignedClasses)
                <form class="p-6 flex-1 flex flex-col gap-5" method="POST" action="{{ route('teacher.attendance.store') }}">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Pilih Kelas</label>
                        <select name="class_id" id="class-id-select" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                            @foreach($classOptions as $musicClass)
                                <option value="{{ $musicClass->id }}" @selected(old('class_id') == $musicClass->id)>{{ $musicClass->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Nama Siswa</label>
                        <select name="student_id" id="student-id-select" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                            <option value="">Pilih siswa</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal</label>
                            <input type="date" name="attendance_date" value="{{ now()->format('Y-m-d') }}" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status</label>
                            <select name="status" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                                <option value="present">Hadir</option>
                                <option value="absent">Absen</option>
                                <option value="late">Terlambat</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Note</label>
                        <textarea name="note" rows="4" placeholder="Catatan progress singkat..." class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-slate-900 text-white text-xs font-bold shadow-lg shadow-slate-100 hover:bg-slate-800 transition-all active:scale-95 flex items-center justify-center gap-2 mt-auto">
                        <i data-lucide="check-square" class="w-4 h-4"></i> SIMPAN ABSEN SISWA
                    </button>
                </form>
            @else
                <div class="p-12 flex flex-col items-center justify-center text-center flex-1">
                    <div class="h-16 w-16 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-4">
                        <i data-lucide="book-x" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">Belum Ada Kelas</h4>
                    <p class="text-xs text-slate-400 mt-2 max-w-[200px]">Anda belum di-assign ke kelas manapun. Hubungi Admin.</p>
                </div>
            @endif
        @else
            <div class="p-12 flex flex-col items-center justify-center text-center flex-1 bg-slate-50/20">
                <div class="h-16 w-16 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-4 border border-slate-100">
                    <i data-lucide="lock" class="w-8 h-8"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Absen Guru Belum Diisi</h4>
                <p class="text-xs text-slate-400 mt-2 max-w-[200px]">Silakan isi absen guru terlebih dahulu sebelum mencatat kehadiran siswa.</p>
            </div>
        @endif
    </section>
</div>

{{-- History Section --}}
<section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-8 mb-10">
    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
        <h3 class="text-base font-bold text-slate-900">Riwayat Absensi Terbaru</h3>
        <div class="flex gap-1 p-1 bg-slate-100 rounded-xl">
            <button class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-all" id="btn-tab-teacher" onclick="switchTab('teacher')">GURU</button>
            <button class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-all" id="btn-tab-student" onclick="switchTab('student')">SISWA</button>
        </div>
    </div>
    
    <div id="tab-teacher-content" class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</th>
                    <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Proof</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($teacherRecords as $row)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-8 py-4 text-xs font-bold text-slate-700">{{ optional($row->attendance_date)->translatedFormat('l, d M Y') }}</td>
                        <td class="px-8 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg {{ $row->status === 'present' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }} text-[9px] font-bold border uppercase">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-[10px] text-slate-400 font-medium max-w-[200px] truncate">{{ $row->location_text ?? '-' }}</td>
                        <td class="px-8 py-4 text-right">
                            @if($row->image_path)
                                <button class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 hover:bg-blue-100 transition-all"><i data-lucide="image" class="w-4 h-4"></i></button>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="tab-student-content" class="overflow-x-auto hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kelas</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Siswa</th>
                    <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($records as $row)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-8 py-4 text-xs text-slate-500">{{ optional($row->attendance_date)->format('d/m/Y') }}</td>
                        <td class="px-8 py-4 text-xs font-bold text-slate-700">{{ $row->musicClass?->name }}</td>
                        <td class="px-8 py-4 text-xs font-bold text-slate-700">{{ $row->student?->name }}</td>
                        <td class="px-8 py-4 text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg {{ $row->status === 'present' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-slate-50 text-slate-500' }} text-[9px] font-bold border uppercase">
                                {{ $row->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<script>
    function switchTab(type) {
        const teacherBtn = document.getElementById('btn-tab-teacher');
        const studentBtn = document.getElementById('btn-tab-student');
        const teacherContent = document.getElementById('tab-teacher-content');
        const studentContent = document.getElementById('tab-student-content');

        if (type === 'teacher') {
            teacherBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            teacherBtn.classList.remove('text-slate-400');
            studentBtn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            studentBtn.classList.add('text-slate-400');
            teacherContent.classList.remove('hidden');
            studentContent.classList.add('hidden');
        } else {
            studentBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            studentBtn.classList.remove('text-slate-400');
            teacherBtn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            teacherBtn.classList.add('text-slate-400');
            studentContent.classList.remove('hidden');
            teacherContent.classList.add('hidden');
        }
    }
    
    // Initialize tab
    switchTab('teacher');

    document.addEventListener('DOMContentLoaded', () => {
        // Student Selection Logic
        const classStudents = @json($classStudents);
        const classSelect = document.getElementById('class-id-select');
        const studentSelect = document.getElementById('student-id-select');
        const oldStudentId = @json(old('student_id'));

        const renderStudentOptions = (classId) => {
            if (!studentSelect) return;
            const students = classStudents[classId] ?? [];
            studentSelect.innerHTML = '<option value="">Pilih siswa</option>';
            students.forEach((student) => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = student.name;
                if (oldStudentId && Number(oldStudentId) === Number(student.id)) option.selected = true;
                studentSelect.appendChild(option);
            });
        };

        if (classSelect) {
            renderStudentOptions(classSelect.value);
            classSelect.addEventListener('change', () => renderStudentOptions(classSelect.value));
        }

        // Camera Logic
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photoPreview = document.getElementById('photo-preview');
        const imageInput = document.getElementById('image-proof-data');
        const btnStart = document.getElementById('btn-start-cam');
        const btnCapture = document.getElementById('btn-capture');
        const btnRetake = document.getElementById('btn-retake');
        const camLoc = document.getElementById('cam-loc');
        const camTime = document.getElementById('cam-time');

        let stream = null;

        btnStart.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                video.srcObject = stream;
                btnStart.classList.add('hidden');
                btnCapture.classList.remove('hidden');
            } catch (err) {
                alert('Gagal mengakses kamera: ' + err.message);
            }
        });

        btnCapture.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Add Watermark to Canvas
            const boxHeight = canvas.height * 0.2;
            context.fillStyle = "rgba(0,0,0,0.5)";
            context.fillRect(0, canvas.height - boxHeight, canvas.width, boxHeight);
            
            context.font = "bold 24px Inter, Arial";
            context.fillStyle = "white";
            context.fillText(camTime.innerText, 30, canvas.height - 70);
            context.font = "18px Inter, Arial";
            context.fillStyle = "rgba(255,255,255,0.8)";
            context.fillText(camLoc.innerText, 30, canvas.height - 40);

            const data = canvas.toDataURL('image/jpeg', 0.8);
            imageInput.value = data;
            
            photoPreview.src = data;
            photoPreview.classList.remove('hidden');
            video.classList.add('hidden');
            
            btnCapture.classList.add('hidden');
            btnRetake.classList.remove('hidden');

            if(stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });

        btnRetake.addEventListener('click', () => {
            photoPreview.classList.add('hidden');
            video.classList.remove('hidden');
            imageInput.value = '';
            btnStart.click();
            btnRetake.classList.add('hidden');
        });

        // Location Logic
        const btnLocation = document.getElementById('btn-get-location');
        const textInput = document.getElementById('teacher-location-text');
        const latInput = document.getElementById('teacher-latitude');
        const lngInput = document.getElementById('teacher-longitude');

        const getLocation = () => {
            if (!navigator.geolocation) {
                textInput.value = 'Browser tidak mendukung GPS';
                return;
            }

            textInput.value = 'Mengambil lokasi...';
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    latInput.value = lat;
                    lngInput.value = lng;
                    textInput.value = `${lat}, ${lng}`;
                    camLoc.innerText = `GPS: ${lat}, ${lng}`;
                    camLoc.classList.remove('bg-blue-600/80');
                    camLoc.classList.add('bg-green-600/80');
                },
                () => {
                    textInput.value = 'Gagal mengambil lokasi';
                    camLoc.innerText = 'GPS Gagal';
                    camLoc.classList.remove('bg-blue-600/80');
                    camLoc.classList.add('bg-red-600/80');
                }
            );
        };

        btnLocation.addEventListener('click', getLocation);
        getLocation(); // Auto-fetch on load
        
        // Timer for watermark
        setInterval(() => {
            const now = new Date();
            camTime.innerText = now.toLocaleString('id-ID');
        }, 1000);
    });
</script>
@endsection
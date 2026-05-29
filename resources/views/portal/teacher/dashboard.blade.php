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
@section('title','Teacher Dashboard')
@section('page-title','Teacher Dashboard')
@section('page-subtitle', 'ROFC Private Music Management Information System')
@section('content')

{{-- Quick Stats Section --}}
<section class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8 mt-2">
    <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <i data-lucide="calendar" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Lessons Today</p>
            <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $todaySchedules->count() }}</h3>
        </div>
    </div>
    <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Completed</p>
            <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $completedCount }}</h3>
        </div>
    </div>
    <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group flex items-center gap-4">
        <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <i data-lucide="clock" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Pending</p>
            <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $todaySchedules->count() - $completedCount }}</h3>
        </div>
    </div>
</section>

{{-- Today's Schedule Section --}}
<section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
        <div>
            <h3 class="text-base font-bold text-slate-900 leading-none">Today's Schedule</h3>
            <p class="text-[11px] font-medium text-slate-400 mt-1">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <i data-lucide="calendar-days" class="w-4 h-4 text-slate-300"></i>
    </div>

    @if($todaySchedules->isNotEmpty())
        <div class="table-wrap">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Time</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Student</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Class</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Address</th>
                    <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($todaySchedules as $schedule)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap" data-label="Time">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-bold border border-blue-100">
                                {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}
                            </span>
                        </td>
                        <td class="px-6 py-4" data-label="Student">
                            <div class="flex items-center gap-3">
                                <div class="h-7 w-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-[9px] uppercase border border-slate-200">
                                    {{ substr($schedule->student?->name, 0, 2) }}
                                </div>
                                <span class="text-sm font-semibold text-slate-700">{{ $schedule->student?->name ?: 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500" data-label="Class">{{ $schedule->musicClass?->name ?: 'N/A' }}</td>
                        <td class="px-6 py-4" data-label="Address">
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                <span class="max-w-[120px] truncate">{{ $schedule->student?->address ?: '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4" data-label="Action">
                                <div class="flex items-center justify-end gap-2">
                                    @php
                                        $pendingRequest = $schedule->rescheduleRequests->where('status', 'pending')->first();
                                    @endphp

                                    @if($pendingRequest)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 text-[10px] font-bold border border-amber-100 uppercase tracking-widest animate-pulse">
                                            RESCHEDULE REQ
                                        </span>
                                    @elseif(now()->addMinutes(30)->lt(\Carbon\Carbon::parse($schedule->session_date->format('Y-m-d') . ' ' . $schedule->time)))
                                        <button type="button" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-500 text-[10px] font-bold shadow-sm hover:bg-slate-50 transition-all active:scale-95"
                                            onclick="openRescheduleModal('{{ $schedule->id }}', '{{ $schedule->schedule_id }}', '{{ $schedule->session_date->translatedFormat('l, d M Y') }} - {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}', '{{ $schedule->teacher_id }}', '{{ $schedule->class_id }}')">
                                            <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                            <span>RESCHEDULE</span>
                                        </button>
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-50 text-slate-400 text-[10px] font-bold border border-slate-100 uppercase tracking-widest">
                                            UPCOMING
                                        </span>
                                    @else
                                        <button type="button" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-500 text-[10px] font-bold shadow-sm hover:bg-slate-50 transition-all active:scale-95"
                                            onclick="openRescheduleModal('{{ $schedule->id }}', '{{ $schedule->schedule_id }}', '{{ $schedule->session_date->translatedFormat('l, d M Y') }} - {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}', '{{ $schedule->teacher_id }}', '{{ $schedule->class_id }}')">
                                            <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                            <span>RESCHEDULE</span>
                                        </button>
                                        <button type="button" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-[10px] font-bold shadow-sm hover:bg-blue-700 transition-all active:scale-95"
                                            onclick="openAttendanceModal('{{ $schedule->id }}', '{{ $schedule->student?->name ?: 'N/A' }}', '{{ $schedule->musicClass?->name ?: 'N/A' }}', '{{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}')">
                                            <i data-lucide="user-check" class="w-3 h-3"></i>
                                            <span>ATTENDANCE</span>
                                        </button>
                                    @endif
                                    <a href="{{ route('teacher.student-progress.input', $schedule->student_id) }}" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-500 text-[10px] font-bold hover:bg-slate-50 transition-all active:scale-95">
                                        <i data-lucide="pencil-line" class="w-3 h-3"></i>
                                        <span>PROGRESS</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="py-10 flex flex-col items-center justify-center text-center">
            <div class="h-14 w-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mb-3">
                <i data-lucide="calendar-x" class="w-6 h-6"></i>
            </div>
            <h4 class="text-slate-900 font-bold text-sm">No schedule for today</h4>
            <p class="text-slate-400 text-xs max-w-xs mx-auto">You don't have any scheduled lessons for today. Take some rest!</p>
        </div>
    @endif
</section>

{{-- Pending Reschedule Requests Section --}}
@if($pendingRescheduleRequests->isNotEmpty())
<section class="bg-white rounded-3xl shadow-sm border-l-4 border-l-amber-500 border border-slate-100 overflow-hidden mb-8">
    <div class="px-8 py-6 bg-amber-50/30">
        <div class="flex items-center gap-3 text-amber-600 mb-1">
            <i data-lucide="refresh-cw" class="w-5 h-5"></i>
            <h3 class="text-lg font-bold">Reschedule Requests</h3>
        </div>
        <p class="text-xs font-medium text-slate-500">Permintaan pindah jadwal dari siswa Anda yang menunggu persetujuan.</p>
    </div>
    <div class="table-wrap">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Student</th>
                    <th class="px-8 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Old Slot</th>
                    <th class="px-8 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">New Slot</th>
                    <th class="px-8 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($pendingRescheduleRequests as $request)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-4 text-sm font-bold text-slate-700" data-label="Student">{{ $request->student->name }}</td>
                        <td class="px-8 py-4 text-xs font-medium text-slate-500" data-label="Old Slot">
                            @if($request->oldSession)
                                {{ $request->oldSession->session_date->translatedFormat('l, d M Y') }} - {{ substr($request->oldSession->time, 0, 5) }}
                            @else
                                {{ ucfirst($request->oldSchedule->day) }} {{ substr($request->oldSchedule->time, 0, 5) }}
                            @endif
                        </td>
                        <td class="px-8 py-4 text-xs font-medium text-slate-500" data-label="New Slot">{{ ucfirst($request->newSchedule->day) }} {{ substr($request->newSchedule->time, 0, 5) }}</td>
                        <td class="px-8 py-4 text-right" data-label="Status">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">
                                PENDING ADMIN
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif

{{-- Premium Attendance Modal (TailwindCSS) --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#2563eb',
                    secondary: '#3b82f6',
                    dark: '#0f172a',
                },
                borderRadius: {
                    '3xl': '1.5rem',
                    '4xl': '2rem',
                }
            }
        }
    }
</script>

<div id="attendanceModal" class="hidden items-center justify-center overflow-hidden px-4 transition-all duration-300" style="position: fixed !important; inset: 0 !important; z-index: 999999 !important;">
    {{-- Glassmorphism Overlay --}}
    <div class="absolute inset-0 bg-slate-900/5 backdrop-blur-[2px] transition-opacity" onclick="closeAttendanceModal()" style="position: absolute !important; inset: 0 !important;"></div>
    
    {{-- Modal Container (Compact & Floating) --}}
    <div class="relative w-full max-w-lg transform overflow-hidden rounded-[28px] bg-white shadow-[0_25px_80px_-15px_rgba(0,0,0,0.3)] transition-all animate-in fade-in zoom-in slide-in-from-bottom-8 duration-500 border border-slate-100">
        
        {{-- Decorative Background --}}
        <div class="absolute top-0 left-0 right-0 h-32 bg-gradient-to-br from-blue-600/5 via-blue-50/10 to-transparent"></div>

        {{-- Header Section --}}
        <header class="relative px-8 pt-8 pb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-5">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-100 uppercase transition-transform duration-300">
                        <i data-lucide="user-check" class="h-7 w-7"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 tracking-tight leading-tight">Mark Attendance</h3>
                        <div class="flex items-center gap-2 mt-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <i data-lucide="clock" class="h-3 w-3 text-blue-500"></i>
                            <span id="realtime-clock" class="text-slate-600">Loading...</span>
                            <span class="text-slate-300">•</span>
                            <span id="modal-current-date">{{ now()->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="closeAttendanceModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-white border border-slate-100 text-slate-400 shadow-sm transition-all hover:bg-red-50 hover:text-red-500 hover:rotate-90">
                    <i data-lucide="x" class="h-4 w-4"></i>
                </button>
            </div>
        </header>

        <div class="max-h-[calc(100vh-10rem)] overflow-y-auto custom-scrollbar px-8 pb-8">


                {{-- Stepper Progress --}}
                <div class="mb-8 flex items-center justify-between px-2">
                    <div id="step-1-indicator" class="flex flex-col items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white ring-4 ring-blue-100">
                            <i data-lucide="camera" class="h-4 w-4"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Photo</span>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-100 mx-2 mb-6" id="line-1"></div>
                    <div id="step-2-indicator" class="flex flex-col items-center gap-2 opacity-40">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                            <i data-lucide="map-pin" class="h-4 w-4"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">GPS Lock</span>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-100 mx-2 mb-6" id="line-2"></div>
                    <div id="step-3-indicator" class="flex flex-col items-center gap-2 opacity-40">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-slate-500">
                            <i data-lucide="check-circle" class="h-4 w-4"></i>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Status</span>
                    </div>
                </div>

                <form action="{{ route('teacher.schedule.attendance.store') }}" method="POST" id="attendance-form">
                    @csrf
                    <input type="hidden" name="session_id" id="modal_session_id">
                    <input type="hidden" name="latitude" id="modal_lat">
                    <input type="hidden" name="longitude" id="modal_lng">
                    <input type="hidden" name="attendance_image" id="modal_image">

                    {{-- Session Details Pill --}}
                    <div class="mb-6 rounded-2xl bg-slate-50 p-4 border border-slate-100">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Student</p>
                                <p id="modal_student_name" class="font-bold text-slate-800"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Class</p>
                                <p id="modal_class_name" class="font-bold text-slate-800"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Camera Section --}}
                    <div class="group relative mb-8 overflow-hidden rounded-3xl border-4 border-slate-100 bg-slate-900 shadow-inner">
                        <div class="relative w-full overflow-hidden flex items-center justify-center bg-black min-h-[300px]">
                            <video id="webcam" autoplay playsinline class="w-full h-auto max-h-[60vh] object-contain"></video>
                            <canvas id="canvas" class="hidden"></canvas>
                            <img id="captured_image" class="hidden w-full h-auto max-h-[60vh] object-contain">
                            
                            {{-- Scanner Effect --}}
                            <div id="scanner-line" class="absolute left-0 top-0 hidden h-1 w-full bg-gradient-to-r from-transparent via-blue-400 to-transparent shadow-[0_0_15px_rgba(96,165,250,0.8)] animate-scan"></div>
                            
                            {{-- Camera Overlay --}}
                            <div id="camera-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/40 backdrop-blur-[2px]">
                                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-md animate-pulse">
                                    <i data-lucide="camera" class="h-8 w-8"></i>
                                </div>
                                <p class="mt-4 text-sm font-medium text-white">Camera Ready</p>
                            </div>

                            {{-- Badges --}}
                            <div class="absolute left-4 top-4 flex flex-col gap-2">
                                <div id="badge-gps" class="flex items-center gap-1.5 rounded-full bg-slate-900/60 px-2.5 py-1 text-[10px] font-bold text-white backdrop-blur-md border border-white/10">
                                    <div class="h-1.5 w-1.5 rounded-full bg-slate-400" id="gps-dot"></div>
                                    GPS STANDBY
                                </div>
                            </div>

                            {{-- Switch Camera Button --}}
                            <div class="absolute right-4 top-4 z-30">
                                <button type="button" onclick="switchCamera()" id="btn_switch_camera" class="hidden flex h-9 w-9 items-center justify-center rounded-full bg-slate-900/60 text-white backdrop-blur-md border border-white/10 transition-all hover:bg-slate-900/80 active:scale-95" title="Switch Camera">
                                    <i data-lucide="refresh-ccw" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Camera Action Bar --}}
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center px-4">
                            <button type="button" onclick="startCamera()" id="btn_start_camera" class="flex items-center gap-2 rounded-2xl bg-white px-6 py-3 text-sm font-bold text-slate-900 shadow-xl transition-all hover:scale-105 active:scale-95">
                                <i data-lucide="play" class="h-4 w-4"></i> START CAMERA
                            </button>
                            <button type="button" onclick="capturePhoto()" id="btn_capture" class="hidden h-14 w-14 items-center justify-center rounded-full bg-white text-blue-600 shadow-xl transition-all hover:scale-110 active:scale-90">
                                <div class="h-10 w-10 rounded-full border-4 border-blue-100 flex items-center justify-center">
                                    <div class="h-6 w-6 rounded-full bg-blue-600"></div>
                                </div>
                            </button>
                            <button type="button" onclick="retakePhoto()" id="btn_retake" class="hidden flex items-center gap-2 rounded-2xl bg-white/20 px-6 py-3 text-sm font-bold text-white shadow-xl backdrop-blur-md transition-all hover:bg-white/30">
                                <i data-lucide="refresh-cw" class="h-4 w-4"></i> RETAKE
                            </button>
                        </div>
                    </div>

                    {{-- Attendance Status Cards --}}
                    <div class="mb-8">
                        <label class="mb-3 block text-xs font-bold uppercase tracking-widest text-slate-400">Attendance Status</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="present" required class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-50 bg-slate-50 p-4 transition-all group-hover:bg-slate-100 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-600 peer-checked:shadow-md">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-400 peer-checked:bg-green-100 peer-checked:text-green-600 shadow-sm">
                                        <i data-lucide="check" class="h-6 w-6"></i>
                                    </div>
                                    <span class="text-xs font-bold">PRESENT</span>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="absent" required class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-50 bg-slate-50 p-4 transition-all group-hover:bg-slate-100 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-600 peer-checked:shadow-md">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-400 peer-checked:bg-red-100 peer-checked:text-red-600 shadow-sm">
                                        <i data-lucide="x" class="h-6 w-6"></i>
                                    </div>
                                    <span class="text-xs font-bold">ABSENT</span>
                                </div>
                            </label>

                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="reschedule" required class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-50 bg-slate-50 p-4 transition-all group-hover:bg-slate-100 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-600 peer-checked:shadow-md">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-400 peer-checked:bg-amber-100 peer-checked:text-amber-600 shadow-sm">
                                        <i data-lucide="calendar-range" class="h-6 w-6"></i>
                                    </div>
                                    <span class="text-xs font-bold">RESCHEDULE</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="mb-4">
                        <label class="mb-3 block text-xs font-bold uppercase tracking-widest text-slate-400">Notes (Optional)</label>
                        <textarea name="note" rows="3" class="w-full rounded-2xl border-2 border-slate-50 bg-slate-50 p-4 text-sm font-medium text-slate-900 placeholder-slate-400 transition-all focus:border-blue-500 focus:bg-white focus:outline-none" placeholder="Add any session notes here..."></textarea>
                    </div>
                </form>


                {{-- Enhanced Footer Actions --}}
                <div class="pt-6 flex gap-3">
                    <button type="button" onclick="closeAttendanceModal()" class="flex-1 rounded-2xl bg-slate-50 py-4 text-[10px] font-extrabold text-slate-500 tracking-widest uppercase transition-all hover:bg-slate-100 active:scale-95">
                        BATALKAN
                    </button>
                    <button type="submit" form="attendance-form" id="btn_submit_attendance" disabled class="flex-[2] rounded-2xl bg-blue-600 py-4 text-[10px] font-extrabold text-white tracking-[0.1em] shadow-xl shadow-blue-100 transition-all hover:bg-blue-700 disabled:bg-slate-100 disabled:text-slate-300 disabled:shadow-none active:scale-95 uppercase">
                        SIMPAN KEHADIRAN
                    </button>
                </div>
            </div>
        </div>
    </div>

<style>
    @keyframes scan {
        0% { top: 0; }
        100% { top: 100%; }
    }
    .animate-scan {
        animation: scan 2.5s linear infinite;
    }
    #attendanceModal.active {
        display: flex;
    }
</style>

<script>
    let stream = null;
    let currentFacingMode = 'environment';

    // Realtime Clock
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const clockEl = document.getElementById('realtime-clock');
        if (clockEl) clockEl.textContent = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();

    function openAttendanceModal(sessionId, studentName, className, time) {
        document.getElementById('modal_session_id').value = sessionId;
        document.getElementById('modal_student_name').textContent = studentName;
        document.getElementById('modal_class_name').textContent = className;
        
        // Reset state
        retakePhoto();
        document.getElementById('attendanceModal').classList.add('active');
        if (window.lucide) window.lucide.createIcons();
    }

    function closeAttendanceModal() {
        stopCamera();
        document.getElementById('attendanceModal').classList.remove('active');
    }

    async function startCamera() {
        const video = document.getElementById('webcam');
        const overlay = document.getElementById('camera-overlay');
        const btnStart = document.getElementById('btn_start_camera');
        const btnCapture = document.getElementById('btn_capture');
        const scanner = document.getElementById('scanner-line');

        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: currentFacingMode },
                audio: false 
            });
            video.srcObject = stream;
            overlay.classList.add('hidden');
            if (scanner) scanner.classList.remove('hidden');
            btnStart.classList.add('hidden');
            btnCapture.classList.remove('hidden');
            const btnSwitch = document.getElementById('btn_switch_camera');
            if (btnSwitch) btnSwitch.classList.remove('hidden');
            
            // Update Stepper
            document.getElementById('step-1-indicator').classList.remove('opacity-40');
        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    function capturePhoto() {
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const img = document.getElementById('captured_image');
        const btnCapture = document.getElementById('btn_capture');
        const btnRetake = document.getElementById('btn_retake');
        const scanner = document.getElementById('scanner-line');
        const submitBtn = document.getElementById('btn_submit_attendance');
        const badgeGps = document.getElementById('badge-gps');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // GPS Lock logic
        if (badgeGps) {
            badgeGps.innerHTML = '<i data-lucide="loader-2" class="h-3 w-3 animate-spin"></i> LOCKING GPS...';
            if (window.lucide) window.lucide.createIcons();
        }
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById('modal_lat').value = lat;
                document.getElementById('modal_lng').value = lng;

                // DRAW OVERLAY ON PHOTO
                const boxHeight = canvas.height * 0.22;
                ctx.fillStyle = 'rgba(15, 23, 42, 0.7)';
                ctx.fillRect(0, canvas.height - boxHeight, canvas.width, boxHeight);

                ctx.fillStyle = 'white';
                const fontSize = Math.floor(canvas.height * 0.035);
                ctx.font = `bold ${fontSize}px Inter, sans-serif`;
                
                const padding = 24;
                const nowText = new Date().toLocaleString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                ctx.fillText(`ROFC ATTENDANCE SYSTEM`, padding, canvas.height - boxHeight + padding + fontSize);
                ctx.font = `${fontSize * 0.8}px Inter, sans-serif`;
                ctx.fillStyle = 'rgba(255,255,255,0.7)';
                ctx.fillText(`Coord: ${lat.toFixed(6)}, ${lng.toFixed(6)}`, padding, canvas.height - boxHeight + padding + (fontSize * 2.5));
                ctx.fillText(nowText, padding, canvas.height - boxHeight + padding + (fontSize * 3.7));

                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                document.getElementById('modal_image').value = dataUrl;
                img.src = dataUrl;
                
                video.classList.add('hidden');
                img.classList.remove('hidden');
                btnCapture.classList.add('hidden');
                btnRetake.classList.remove('hidden');
                const btnSwitch = document.getElementById('btn_switch_camera');
                if (btnSwitch) btnSwitch.classList.add('hidden');
                if (scanner) scanner.classList.add('hidden');
                
                // Update Badge
                if (badgeGps) {
                    badgeGps.innerHTML = '<div class="h-1.5 w-1.5 rounded-full bg-green-500"></div> GPS LOCKED';
                    badgeGps.classList.remove('bg-slate-900/60');
                    badgeGps.classList.add('bg-green-600/80');
                    if (window.lucide) window.lucide.createIcons();
                }

                // Update Stepper
                document.getElementById('step-2-indicator').classList.remove('opacity-40');
                document.getElementById('step-3-indicator').classList.remove('opacity-40');
                document.getElementById('line-1').classList.add('bg-blue-600');
                document.getElementById('line-2').classList.add('bg-blue-600');

                submitBtn.disabled = false;
                stopCamera();
            },
            (err) => {
                alert('GPS Error: ' + err.message);
                if (badgeGps) badgeGps.innerHTML = '<div class="h-1.5 w-1.5 rounded-full bg-red-500"></div> GPS FAILED';
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    }

    function retakePhoto() {
        const video = document.getElementById('webcam');
        const img = document.getElementById('captured_image');
        const btnStart = document.getElementById('btn_start_camera');
        const btnCapture = document.getElementById('btn_capture');
        const btnRetake = document.getElementById('btn_retake');
        const overlay = document.getElementById('camera-overlay');
        const badgeGps = document.getElementById('badge-gps');
        const submitBtn = document.getElementById('btn_submit_attendance');

        if (video) video.classList.remove('hidden');
        if (img) {
            img.classList.add('hidden');
            img.src = '';
        }
        document.getElementById('modal_image').value = '';
        
        if (btnStart) btnStart.classList.remove('hidden');
        if (btnCapture) btnCapture.classList.add('hidden');
        if (btnRetake) btnRetake.classList.add('hidden');
        if (overlay) overlay.classList.remove('hidden');
        const btnSwitch = document.getElementById('btn_switch_camera');
        if (btnSwitch) btnSwitch.classList.add('hidden');
        
        if (badgeGps) {
            badgeGps.innerHTML = '<div class="h-1.5 w-1.5 rounded-full bg-slate-400"></div> GPS STANDBY';
            badgeGps.classList.add('bg-slate-900/60');
            badgeGps.classList.remove('bg-green-600/80');
        }
        
        // Reset Stepper
        document.getElementById('step-2-indicator').classList.add('opacity-40');
        document.getElementById('step-3-indicator').classList.add('opacity-40');
        document.getElementById('line-1').classList.remove('bg-blue-600');
        document.getElementById('line-2').classList.remove('bg-blue-600');

        submitBtn.disabled = true;
        if (window.lucide) window.lucide.createIcons();
    }

    async function switchCamera() {
        currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
        stopCamera();
        await startCamera();
    }
</script>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="hidden items-center justify-center overflow-hidden px-4 transition-all duration-300" style="position: fixed !important; inset: 0 !important; z-index: 999999 !important;">
    <div class="absolute inset-0 bg-slate-900/5 backdrop-blur-[2px] transition-opacity" onclick="closeRescheduleModal()" style="position: absolute !important; inset: 0 !important;"></div>
    
    <div class="relative w-full max-w-lg scale-95 opacity-0 transition-all duration-300" id="rescheduleModalContent">
        <div class="relative overflow-hidden rounded-[2rem] bg-white shadow-2xl ring-1 ring-slate-100">
            <!-- Header -->
            <div class="relative bg-white px-8 pt-8 pb-6 border-b border-slate-50">
                <div class="absolute right-6 top-6">
                    <button type="button" onclick="closeRescheduleModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-all">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
                <div>
                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-500 shadow-inner">
                        <i data-lucide="refresh-cw" class="h-6 w-6"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight leading-tight">Request Reschedule</h3>
                    <p class="mt-1.5 text-xs font-medium text-slate-400">Pilih slot jadwal pengganti yang tersedia untuk sesi ini.</p>
                </div>
            </div>

            <!-- Body -->
            <form action="{{ route('teacher.schedule.reschedule.request') }}" method="POST" id="rescheduleForm" class="px-8 py-6 flex flex-col gap-5">
                @csrf
                <input type="hidden" name="old_session_id" id="old_session_id">
                
                <!-- Current Schedule Box -->
                <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                    <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Jadwal Saat Ini</label>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                        <p id="old_schedule_label_text" class="text-xs font-bold text-slate-700">Loading...</p>
                    </div>
                </div>

                <!-- New Schedule Select Box -->
                <div class="rounded-2xl border border-slate-200 bg-white p-4 focus-within:border-blue-400 focus-within:ring-4 focus-within:ring-blue-50 transition-all">
                    <label for="new_schedule_id" class="mb-2 block text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Jadwal Pengganti</label>
                    <div class="flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4 text-slate-400"></i>
                        <select name="new_schedule_id" id="new_schedule_id" required 
                            class="w-full bg-transparent text-slate-700 text-xs font-bold outline-none cursor-pointer appearance-none border-none p-0 focus:ring-0">
                            <option value="">-- Pilih Slot Baru --</option>
                        </select>
                    </div>
                </div>

                <!-- Reason Box -->
                <div class="rounded-2xl border border-slate-200 bg-white p-4 focus-within:border-blue-400 focus-within:ring-4 focus-within:ring-blue-50 transition-all">
                    <label for="reason" class="mb-2 block text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Alasan Reschedule (Opsional)</label>
                    <textarea name="reason" id="reason" rows="2" 
                        class="w-full bg-transparent text-slate-700 text-xs font-medium outline-none resize-none border-none p-0 focus:ring-0 placeholder:text-slate-300"
                        placeholder="Tuliskan alasan singkat..."></textarea>
                </div>

                <!-- Footer Buttons -->
                <div class="mt-2 flex gap-3">
                    <button type="button" onclick="closeRescheduleModal()" class="flex-1 rounded-2xl bg-slate-50 py-3.5 text-[10px] font-extrabold text-slate-500 tracking-widest uppercase transition-all hover:bg-slate-100 active:scale-95">
                        Batal
                    </button>
                    <button type="submit" class="flex-[2] rounded-2xl bg-amber-500 py-3.5 text-[10px] font-extrabold text-white tracking-[0.1em] shadow-xl shadow-amber-100 transition-all hover:bg-amber-600 active:scale-95 uppercase flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i> Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #rescheduleModal.active { display: flex !important; }
    #rescheduleModal.active #rescheduleModalContent { transform: scale(1); opacity: 1; }
</style>

<script>
    function openRescheduleModal(oldId, scheduleId, oldLabel, teacherId, classId) {
        document.getElementById('old_session_id').value = oldId;
        document.getElementById('old_schedule_label_text').textContent = oldLabel;
        
        const modal = document.getElementById('rescheduleModal');
        modal.style.display = 'flex';
        // force reflow
        void modal.offsetWidth;
        modal.classList.add('active');

        const select = document.getElementById('new_schedule_id');
        select.innerHTML = '<option value="">Loading slots...</option>';

        fetch(`/teacher/schedule/available-slots?teacher_id=${teacherId}&class_id=${classId}`)
            .then(res => res.json())
            .then(data => {
                select.innerHTML = '<option value="">-- Pilih Slot Baru --</option>';

                const pushGroup = document.createElement('optgroup');
                pushGroup.label = "Opsi Jadwal Rutin";
                const pushOpt = document.createElement('option');
                pushOpt.value = scheduleId;
                pushOpt.textContent = "➡️ Lewati minggu ini (Dorong Mundur 1 Minggu)";
                pushGroup.appendChild(pushOpt);
                select.appendChild(pushGroup);

                if (data.grouped) {
                    Object.keys(data.grouped).forEach(day => {
                        const group = document.createElement('optgroup');
                        group.label = day;
                        data.grouped[day].forEach(slot => {
                            const opt = document.createElement('option');
                            opt.value = slot.id;
                            opt.textContent = `${slot.date_label} - ${slot.time}`;
                            group.appendChild(opt);
                        });
                        select.appendChild(group);
                    });
                }
                if (window.lucide) window.lucide.createIcons();
            });
    }

    function closeRescheduleModal() {
        const modal = document.getElementById('rescheduleModal');
        modal.classList.remove('active');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
</script>
@endsection

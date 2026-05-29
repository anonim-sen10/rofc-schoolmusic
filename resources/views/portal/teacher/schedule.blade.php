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

@section('title', 'My Schedule')
@section('page-title', 'Teaching Schedule')
@section('page-subtitle', 'Pantau dan kelola seluruh jadwal sesi mengajar Anda dengan sistem absensi GPS terintegrasi.')

@section('content')

<section class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden mb-8 mt-2">
    <div class="px-8 py-5 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
        <div>
            <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Jadwal Mengajar Saya</h3>
            <p class="text-[10px] font-semibold text-slate-400 mt-1 flex items-center gap-2">
                <i data-lucide="info" class="w-3 h-3 text-blue-400"></i>
                Daftar seluruh sesi mengajar aktif dan riwayat pertemuan Anda.
            </p>
        </div>
        <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/50">
            <i data-lucide="calendar-check-2" class="w-5 h-5"></i>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-slate-50/60">
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Jadwal Sesi</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Informasi Siswa</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Materi Kelas</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Lokasi</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Status</th>
                    <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50/80">
                @forelse($schedules as $schedule)
                    <tr class="hover:bg-blue-50/20 transition-all duration-300 group">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center w-fit px-2.5 py-1 rounded-lg bg-blue-600 text-white text-[10px] font-extrabold shadow-sm tracking-tight">
                                    {{ $schedule->session_date->translatedFormat('l, d M Y') }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-0.5 text-[11px] font-bold text-slate-600">
                                    <i data-lucide="clock" class="w-3 h-3 text-blue-500"></i>
                                    {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }} WIB
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-slate-700 font-extrabold text-xs uppercase group-hover:scale-105 transition-transform duration-300">
                                    @php
                                        $initials = substr($schedule->student->user->name ?? ($schedule->student->name ?? '-'), 0, 2);
                                    @endphp
                                    <span class="bg-gradient-to-br from-slate-700 to-slate-900 bg-clip-text text-transparent">{{ $initials }}</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-extrabold text-slate-800 leading-none">{{ $schedule->student->user->name ?? ($schedule->student->name ?? '-') }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">ID: {{ str_pad($schedule->student_id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs font-bold text-slate-700">{{ $schedule->musicClass->name ?? '-' }}</span>
                                <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Private Session</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-start gap-1.5 text-[11px] font-semibold text-slate-500 max-w-[160px]">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-300 mt-0.5 shrink-0"></i>
                                <span class="leading-snug">{{ $schedule->student->address ?? 'Alamat belum tersedia' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            @php
                                $pendingRequest = $schedule->rescheduleRequests->where('status', 'pending')->first();
                            @endphp

                            @if(!$schedule->student->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[9px] font-extrabold border border-slate-200 tracking-wider">INACTIVE</span>
                            @elseif($schedule->status === 'rescheduled')
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[9px] font-extrabold border border-slate-200 tracking-wider">RESCHEDULED</span>
                            @elseif($pendingRequest)
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-[9px] font-extrabold border border-amber-200 tracking-wider animate-pulse">RESCHEDULE REQ</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-[9px] font-extrabold border border-green-200 tracking-wider">ACTIVE</span>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end">
                                @if(!$schedule->student->is_active)
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 text-slate-500 border border-slate-200">
                                        <i data-lucide="user-x" class="w-3.5 h-3.5"></i>
                                        <span class="text-[9px] font-extrabold tracking-widest uppercase">Inactive</span>
                                    </div>
                                @elseif($schedule->status === 'completed')
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-green-50 text-green-700 border border-green-100">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                        <span class="text-[9px] font-extrabold tracking-widest uppercase">Finished</span>
                                    </div>
                                @elseif($schedule->status === 'rescheduled')
                                    <span class="text-[10px] font-bold text-slate-300 tracking-widest uppercase italic opacity-60">Rescheduled</span>
                                @elseif($schedule->attendance)
                                    @php 
                                        $status = strtolower($schedule->attendance->status);
                                        $class = $status === 'present' ? 'bg-blue-50 text-blue-700 border-blue-100' : ($status === 'absent' ? 'bg-red-50 text-red-700 border-red-100' : 'bg-amber-50 text-amber-700 border-amber-100');
                                    @endphp
                                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl {{ $class }} border">
                                        <i data-lucide="{{ $status === 'present' ? 'check' : ($status === 'absent' ? 'x' : 'clock') }}" class="w-3.5 h-3.5"></i>
                                        <span class="text-[9px] font-extrabold tracking-widest uppercase">{{ $status }}</span>
                                    </div>
                                @elseif(now()->addMinutes(30)->lt(\Carbon\Carbon::parse($schedule->session_date->format('Y-m-d') . ' ' . $schedule->time)))
                                    <div class="flex items-center gap-2">
                                        @if(!$pendingRequest)
                                            <button type="button" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-500 text-[10px] font-bold shadow-sm hover:bg-slate-50 transition-all active:scale-95"
                                                onclick="openRescheduleModal('{{ $schedule->id }}', '{{ $schedule->schedule_id }}', '{{ $schedule->session_date->translatedFormat('l, d M Y') }} - {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}', '{{ $schedule->teacher_id }}', '{{ $schedule->class_id }}')">
                                                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                                <span>RESCHEDULE</span>
                                            </button>
                                        @endif
                                        <span class="text-[10px] font-bold text-slate-300 tracking-widest uppercase italic opacity-60">Upcoming</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        @if(!$pendingRequest)
                                            <button type="button" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-500 text-[10px] font-bold shadow-sm hover:bg-slate-50 transition-all active:scale-95"
                                                onclick="openRescheduleModal('{{ $schedule->id }}', '{{ $schedule->schedule_id }}', '{{ $schedule->session_date->translatedFormat('l, d M Y') }} - {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}', '{{ $schedule->teacher_id }}', '{{ $schedule->class_id }}')">
                                                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                                <span>RESCHEDULE</span>
                                            </button>
                                        @endif
                                        <button type="button" 
                                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-[10px] font-extrabold shadow-lg shadow-blue-100 hover:bg-blue-700 active:scale-95 transition-all duration-300"
                                            onclick="openAttendanceModal('{{ $schedule->id }}', '{{ $schedule->student->name }}', '{{ $schedule->musicClass->name }}', '{{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}')">
                                            <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                                            <span>ATTENDANCE</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-16 w-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200 mb-4 border border-slate-100">
                                    <i data-lucide="calendar-off" class="w-8 h-8"></i>
                                </div>
                                <h4 class="text-slate-900 font-extrabold text-base">Belum ada jadwal mengajar</h4>
                                <p class="text-slate-400 text-xs max-w-sm mx-auto mt-1 font-medium">Anda belum memiliki jadwal sesi musik yang terdaftar untuk periode ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@push('modals')
{{-- Premium Attendance Modal (TailwindCSS) --}}
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
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-100 ring-2 ring-blue-50">
                            <i data-lucide="camera" class="h-4 w-4"></i>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-blue-600">Photo Proof</span>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-100 mx-2 mb-5 rounded-full overflow-hidden" id="line-1-container">
                        <div class="h-full w-0 bg-blue-600 transition-all duration-700" id="line-1"></div>
                    </div>
                    <div id="step-2-indicator" class="flex flex-col items-center gap-2 opacity-30">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                            <i data-lucide="map-pin" class="h-4 w-4"></i>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">GPS Lock</span>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-100 mx-2 mb-5 rounded-full overflow-hidden" id="line-2-container">
                        <div class="h-full w-0 bg-blue-600 transition-all duration-700" id="line-2"></div>
                    </div>
                    <div id="step-3-indicator" class="flex flex-col items-center gap-2 opacity-30">
                        <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                            <i data-lucide="check-circle" class="h-4 w-4"></i>
                        </div>
                        <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Finalize</span>
                    </div>
                </div>

                <form action="{{ route('teacher.schedule.attendance.store') }}" method="POST" id="attendance-form">
                    @csrf
                    <input type="hidden" name="session_id" id="modal_session_id">
                    <input type="hidden" name="latitude" id="modal_lat">
                    <input type="hidden" name="longitude" id="modal_lng">
                    <input type="hidden" name="attendance_image" id="modal_image">

                    {{-- Session Information Card --}}
                    <div class="mb-6 rounded-2xl bg-slate-50/50 p-4 border border-slate-100 flex flex-col gap-3">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Siswa</p>
                                <p id="modal_student_name" class="text-xs font-extrabold text-slate-800 leading-tight"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Kelas</p>
                                <p id="modal_class_name" class="text-xs font-extrabold text-slate-800 leading-tight"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Camera/Image Capture Section --}}
                    <div class="group relative mb-8 overflow-hidden rounded-2xl border-[4px] border-slate-50 bg-slate-900 shadow-xl">
                        <div class="relative w-full overflow-hidden flex items-center justify-center bg-black min-h-[300px]">
                            <video id="webcam" autoplay playsinline class="w-full h-auto max-h-[60vh] object-contain grayscale-[0.2]"></video>
                            <canvas id="canvas" class="hidden"></canvas>
                            <img id="captured_image" class="hidden w-full h-auto max-h-[60vh] object-contain">
                            
                            {{-- Scanner Animation Effect --}}
                            <div id="scanner-line" class="absolute left-0 top-0 hidden h-1 w-full bg-gradient-to-r from-transparent via-blue-400 to-transparent shadow-[0_0_20px_rgba(96,165,250,1)] animate-scan z-10"></div>
                            
                            {{-- Initial Camera Overlay --}}
                            <div id="camera-overlay" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/40 backdrop-blur-[2px] z-20">
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 text-white backdrop-blur-xl border border-white/20 animate-pulse">
                                    <i data-lucide="camera" class="h-8 w-8"></i>
                                </div>
                                <p class="mt-4 text-[10px] font-extrabold tracking-[0.2em] text-white uppercase">System Ready</p>
                            </div>

                            {{-- Floating Badges --}}
                            <div class="absolute left-4 top-4 flex flex-col gap-2 z-30">
                                <div id="badge-gps" class="flex items-center gap-1.5 rounded-full bg-slate-900/60 px-2.5 py-1 text-[9px] font-extrabold text-white backdrop-blur-md border border-white/10 shadow-lg">
                                    <div class="h-1.5 w-1.5 rounded-full bg-slate-400 animate-pulse" id="gps-dot"></div>
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

                        {{-- Dynamic Action Controls --}}
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center px-4 z-30">
                            <button type="button" onclick="startCamera()" id="btn_start_camera" class="flex items-center gap-2.5 rounded-xl bg-white px-6 py-3 text-xs font-extrabold text-slate-900 shadow-2xl transition-all hover:scale-105 hover:bg-slate-50 active:scale-95 group">
                                <i data-lucide="play" class="h-4 w-4 text-blue-600 group-hover:rotate-12 transition-transform"></i> 
                                <span class="tracking-tight uppercase">Aktifkan Kamera</span>
                            </button>
                            <button type="button" onclick="capturePhoto()" id="btn_capture" class="hidden h-14 w-14 items-center justify-center rounded-full bg-white text-blue-600 shadow-2xl transition-all hover:scale-110 active:scale-90 border-[4px] border-blue-50">
                                <div class="h-6 w-6 rounded-full bg-blue-600 shadow-inner"></div>
                            </button>
                            <button type="button" onclick="retakePhoto()" id="btn_retake" class="hidden flex items-center gap-2.5 rounded-xl bg-white/10 px-6 py-3 text-xs font-extrabold text-white shadow-2xl backdrop-blur-xl border border-white/20 transition-all hover:bg-white/20 active:scale-95">
                                <i data-lucide="refresh-cw" class="h-4 w-4"></i> 
                                <span class="tracking-tight uppercase">Ambil Ulang</span>
                            </button>
                        </div>
                    </div>

                    {{-- Status Selection Grid --}}
                    <div class="mb-8">
                        <label class="mb-3 block text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Konfirmasi Kehadiran</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="present" required class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-50 bg-slate-50/50 p-4 transition-all duration-300 group-hover:bg-slate-100 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 peer-checked:shadow-lg peer-checked:shadow-blue-100/50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-300 peer-checked:bg-blue-600 peer-checked:text-white shadow-sm transition-all duration-300">
                                        <i data-lucide="check" class="h-5 w-5"></i>
                                    </div>
                                    <span class="text-[9px] font-extrabold tracking-widest uppercase">HADIR</span>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="absent" required class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-50 bg-slate-50/50 p-4 transition-all duration-300 group-hover:bg-slate-100 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 peer-checked:shadow-lg peer-checked:shadow-red-100/50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-300 peer-checked:bg-red-600 peer-checked:text-white shadow-sm transition-all duration-300">
                                        <i data-lucide="x" class="h-5 w-5"></i>
                                    </div>
                                    <span class="text-[9px] font-extrabold tracking-widest uppercase">ABSEN</span>
                                </div>
                            </label>

                            <label class="relative cursor-pointer group">
                                <input type="radio" name="status" value="reschedule" required class="peer sr-only">
                                <div class="flex flex-col items-center gap-2 rounded-2xl border-2 border-slate-50 bg-slate-50/50 p-4 transition-all duration-300 group-hover:bg-slate-100 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700 peer-checked:shadow-lg peer-checked:shadow-amber-100/50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-300 peer-checked:bg-amber-600 peer-checked:text-white shadow-sm transition-all duration-300">
                                        <i data-lucide="calendar-range" class="h-5 w-5"></i>
                                    </div>
                                    <span class="text-[9px] font-extrabold tracking-widest uppercase">ULANG</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Interaction Feedback Section --}}
                    <div class="mb-2">
                        <label class="mb-3 block text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Catatan Pembelajaran (Opsional)</label>
                        <textarea name="note" rows="2" class="w-full rounded-2xl border-2 border-slate-50 bg-slate-50/50 p-4 text-xs font-bold text-slate-700 placeholder-slate-400 transition-all focus:border-blue-500 focus:bg-white focus:outline-none" placeholder="Tulis catatan perkembangan siswa..."></textarea>
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
@endpush

<style>
    @keyframes scan {
        0% { top: 0; }
        100% { top: 100%; }
    }
    .animate-scan {
        animation: scan 2.5s linear infinite;
    }
    #attendanceModal.active {
        display: flex !important;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>

<script>
    let stream = null;
    let currentFacingMode = 'environment';

    // Enhanced Realtime Clock
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const clockEl = document.getElementById('realtime-clock');
        if (clockEl) clockEl.textContent = timeStr + ' WIB';
    }
    setInterval(updateClock, 1000);
    updateClock();

    function openAttendanceModal(sessionId, studentName, className, time) {
        const modal = document.getElementById('attendanceModal');
        const sessIdInput = document.getElementById('modal_session_id');
        const studNameEl = document.getElementById('modal_student_name');
        const classNameEl = document.getElementById('modal_class_name');

        if (sessIdInput) sessIdInput.value = sessionId;
        if (studNameEl) studNameEl.textContent = studentName;
        if (classNameEl) classNameEl.textContent = className;
        
        // Reset state
        retakePhoto();
        modal.classList.add('active');
        if (window.lucide) window.lucide.createIcons();
        document.body.style.overflow = 'hidden';
    }

    function closeAttendanceModal() {
        stopCamera();
        document.getElementById('attendanceModal').classList.remove('active');
        document.body.style.overflow = 'auto';
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
            const step1 = document.getElementById('step-1-indicator');
            if (step1) step1.classList.remove('opacity-30');
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

        // GPS Lock logic with visual feedback
        if (badgeGps) {
            badgeGps.innerHTML = '<i data-lucide="loader-2" class="h-3 w-3 animate-spin text-blue-400"></i> SECURING GPS...';
            if (window.lucide) window.lucide.createIcons();
        }
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const latInput = document.getElementById('modal_lat');
                const lngInput = document.getElementById('modal_lng');
                const imgInput = document.getElementById('modal_image');

                if (latInput) latInput.value = lat;
                if (lngInput) lngInput.value = lng;

                // PREMIUM PHOTO WATERMARK
                const boxHeight = canvas.height * 0.25;
                const gradient = ctx.createLinearGradient(0, canvas.height - boxHeight, 0, canvas.height);
                gradient.addColorStop(0, 'transparent');
                gradient.addColorStop(1, 'rgba(15, 23, 42, 0.9)');
                ctx.fillStyle = gradient;
                ctx.fillRect(0, canvas.height - boxHeight, canvas.width, boxHeight);

                ctx.fillStyle = 'white';
                const fontSize = Math.floor(canvas.height * 0.04);
                ctx.font = `bold ${fontSize}px Inter, sans-serif`;
                
                const paddingX = 40;
                const paddingY = 40;
                const nowText = new Date().toLocaleString('id-ID', { 
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });

                ctx.shadowColor = "rgba(0, 0, 0, 0.5)";
                ctx.shadowBlur = 10;
                ctx.fillText(`ROFC ATTENDANCE VERIFIED`, paddingX, canvas.height - paddingY - (fontSize * 2));
                
                ctx.shadowBlur = 0;
                ctx.font = `${fontSize * 0.75}px Inter, sans-serif`;
                ctx.fillStyle = 'rgba(255,255,255,0.85)';
                ctx.fillText(`Coordinates: ${lat.toFixed(6)}, ${lng.toFixed(6)}`, paddingX, canvas.height - paddingY - fontSize);
                ctx.fillText(`Timestamp: ${nowText} WIB`, paddingX, canvas.height - paddingY);

                const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                if (imgInput) imgInput.value = dataUrl;
                img.src = dataUrl;
                
                video.classList.add('hidden');
                img.classList.remove('hidden');
                btnCapture.classList.add('hidden');
                btnRetake.classList.remove('hidden');
                const btnSwitch = document.getElementById('btn_switch_camera');
                if (btnSwitch) btnSwitch.classList.add('hidden');
                if (scanner) scanner.classList.add('hidden');
                
                // Update GPS Badge to success
                if (badgeGps) {
                    badgeGps.innerHTML = '<div class="h-2 w-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div> LOCATION VERIFIED';
                    badgeGps.classList.remove('bg-slate-900/60');
                    badgeGps.classList.add('bg-green-600/90');
                    if (window.lucide) window.lucide.createIcons();
                }

                // Smoothly update Stepper progress
                const step2 = document.getElementById('step-2-indicator');
                const step3 = document.getElementById('step-3-indicator');
                const line1 = document.getElementById('line-1');
                const line2 = document.getElementById('line-2');

                if (step2) step2.classList.remove('opacity-30');
                if (step3) step3.classList.remove('opacity-30');
                if (line1) line1.style.width = '100%';
                if (line2) line2.style.width = '100%';

                if (submitBtn) submitBtn.disabled = false;
                stopCamera();
            },
            (err) => {
                alert('GPS Error: Mohon aktifkan lokasi browser Anda. ' + err.message);
                if (badgeGps) {
                    badgeGps.innerHTML = '<div class="h-2 w-2 rounded-full bg-red-500"></div> GPS FAILED';
                    badgeGps.classList.add('bg-red-600/90');
                }
            },
            { enableHighAccuracy: true, timeout: 10000 }
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
        const imgInput = document.getElementById('modal_image');
        if (imgInput) imgInput.value = '';
        
        if (btnStart) btnStart.classList.remove('hidden');
        if (btnCapture) btnCapture.classList.add('hidden');
        if (btnRetake) btnRetake.classList.add('hidden');
        if (overlay) overlay.classList.remove('hidden');
        const btnSwitch = document.getElementById('btn_switch_camera');
        if (btnSwitch) btnSwitch.classList.add('hidden');
        
        if (badgeGps) {
            badgeGps.innerHTML = '<div class="h-2 w-2 rounded-full bg-slate-400"></div> GPS STANDBY';
            badgeGps.classList.add('bg-slate-900/60');
            badgeGps.classList.remove('bg-green-600/90', 'bg-red-600/90');
        }
        
        // Reset Stepper
        const step2 = document.getElementById('step-2-indicator');
        const step3 = document.getElementById('step-3-indicator');
        const line1 = document.getElementById('line-1');
        const line2 = document.getElementById('line-2');

        if (step2) step2.classList.add('opacity-30');
        if (step3) step3.classList.add('opacity-30');
        if (line1) line1.style.width = '0%';
        if (line2) line2.style.width = '0%';

        if (submitBtn) submitBtn.disabled = true;
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

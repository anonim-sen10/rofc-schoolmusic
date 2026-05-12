@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index')],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index')],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index')],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index')],
        ['label' => 'Materials', 'url' => route('teacher.materials.index')],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp

@extends('portal.layouts.app')

@section('title', 'My Students')
@section('page-title', 'My Students')
@section('page-subtitle', 'Manajemen daftar siswa dan pemantauan perkembangan.')

@section('content')
    {{-- Modern Student Detail Modal (Compact & Floating) --}}
    <div id="studentDetailModal" class="hidden items-center justify-center overflow-hidden px-4 transition-all duration-300" style="position: fixed !important; inset: 0 !important; z-index: 999999 !important;">
        {{-- Fullscreen Overlay --}}
        <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-[2px] transition-opacity" onclick="closeStudentModal()" style="position: absolute !important; inset: 0 !important;"></div>
        
        {{-- Modal Container (Compact) --}}
        <div class="relative w-full max-w-md transform overflow-hidden rounded-[28px] bg-white shadow-[0_25px_80px_-15px_rgba(0,0,0,0.4)] transition-all animate-in fade-in zoom-in slide-in-from-bottom-8 duration-500 ease-out">
            {{-- Decorative Background --}}
            <div class="absolute top-0 left-0 right-0 h-24 bg-gradient-to-br from-blue-600/5 via-blue-50/20 to-transparent"></div>

            <header class="relative px-8 pt-8 pb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div id="modal-avatar" class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white font-black text-lg shadow-lg shadow-blue-100 uppercase transition-transform duration-300">
                            -
                        </div>
                        <div>
                            <h3 id="modal-name" class="text-xl font-black text-slate-900 tracking-tight leading-tight">-</h3>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span id="modal-status-badge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold shadow-sm">-</span>
                                <div class="h-1 w-1 rounded-full bg-slate-200"></div>
                                <span class="text-slate-400 text-[9px] font-bold uppercase tracking-widest">ID: #<span id="modal-id">-</span></span>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="closeStudentModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-white border border-slate-100 text-slate-400 shadow-sm transition-all hover:bg-red-50 hover:text-red-500 hover:rotate-90">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
            </header>

            <div class="px-8 pb-8">
                <div class="grid grid-cols-1 gap-4">
                    {{-- Class Information Card --}}
                    <div class="group p-4 rounded-2xl bg-slate-50 border border-slate-100 transition-all hover:bg-white hover:border-blue-100">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-blue-600">
                                <i data-lucide="book-open" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Class</p>
                                <p id="modal-classes" class="text-sm font-extrabold text-slate-800 leading-none">-</p>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Info Grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-4 rounded-2xl bg-white border border-slate-100 shadow-sm">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 leading-none">Phone</p>
                            <div class="flex items-center gap-2 text-[11px] font-bold text-slate-700">
                                <i data-lucide="phone" class="w-3 h-3 text-blue-500"></i>
                                <span id="modal-phone">-</span>
                            </div>
                        </div>
                        <div class="p-4 rounded-2xl bg-white border border-slate-100 shadow-sm">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 leading-none">Email</p>
                            <div class="flex items-center gap-2 text-[11px] font-bold text-slate-700">
                                <i data-lucide="mail" class="w-3 h-3 text-blue-500"></i>
                                <span id="modal-email" class="truncate">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- Address Section --}}
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2 leading-none">Address</p>
                        <div class="flex items-start gap-2.5">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-300 mt-0.5"></i>
                            <p id="modal-address" class="text-[11px] font-semibold text-slate-600 leading-relaxed">-</p>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="pt-4 flex gap-3">
                        <a id="modal-progress-link" href="#" class="flex-[2] inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-900 text-white text-[11px] font-bold transition-all hover:bg-slate-800 shadow-lg shadow-slate-100 no-underline">
                            <i data-lucide="pencil-line" class="w-3.5 h-3.5"></i>
                            INPUT PROGRESS
                        </a>
                        <button type="button" onclick="closeStudentModal()" class="flex-1 px-5 py-3.5 rounded-2xl bg-slate-50 text-slate-500 text-[11px] font-bold transition-all hover:bg-slate-100 no-underline">
                            CLOSE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Students Table Card --}}
    <section class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div>
                <h3 class="text-lg font-bold text-slate-900 leading-none">Daftar Seluruh Siswa</h3>
                <p class="text-xs font-medium text-slate-400 mt-1">Total: {{ $students->count() }} Siswa</p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400">
                <i data-lucide="graduation-cap" class="w-5 h-5"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Student</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Class</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($students as $student)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-[10px] uppercase border border-slate-200">
                                        {{ substr($student->name, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                @php
                                    $classNames = $student->classes->pluck('name')->toArray();
                                    if (empty($classNames)) {
                                        $classNames = $student->scheduleSessions->pluck('musicClass.name')->filter()->unique()->toArray();
                                    }
                                    $displayClass = !empty($classNames) ? implode(', ', $classNames) : '-';
                                @endphp
                                <span class="text-xs font-medium text-slate-500">{{ $displayClass }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg {{ $student->is_active ? 'bg-green-50 text-green-700 border-green-100' : 'bg-amber-50 text-amber-700 border-amber-100' }} text-[10px] font-bold border">
                                    {{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-end gap-2 transition-opacity">
                                    <button type="button" 
                                        onclick="showStudentDetailModal({
                                            id: '{{ $student->id }}',
                                            name: '{{ addslashes($student->name) }}',
                                            classes: '{{ addslashes($displayClass) }}',
                                            status: '{{ $student->is_active ? 'ACTIVE' : 'INACTIVE' }}',
                                            phone: '{{ $student->phone ?? '-' }}',
                                            email: '{{ $student->email ?? '-' }}',
                                            address: '{{ addslashes($student->address ?? '-') }}'
                                        })"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 text-white text-[10px] font-bold shadow-sm shadow-blue-100 hover:bg-blue-700 hover:shadow-md transition-all active:scale-95 no-underline">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        <span>DETAIL</span>
                                    </button>
                                    <a href="{{ route('teacher.student-progress.input', ['student_id' => $student->id]) }}" 
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-[10px] font-bold hover:bg-slate-50 transition-all active:scale-95 no-underline">
                                        <i data-lucide="pencil-line" class="w-3.5 h-3.5"></i>
                                        <span>INPUT PROGRESS</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                                        <i data-lucide="user-x" class="w-8 h-8"></i>
                                    </div>
                                    <h4 class="text-slate-900 font-bold text-sm">Belum ada siswa</h4>
                                    <p class="text-slate-400 text-xs mt-1">Siswa akan muncul di sini setelah mereka terdaftar di kelas Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>



    <script>
        function showStudentDetailModal(data) {
            const modal = document.getElementById('studentDetailModal');
            
            // Set Data
            document.getElementById('modal-id').textContent = data.id;
            document.getElementById('modal-name').textContent = data.name;
            document.getElementById('modal-avatar').textContent = data.name.substring(0, 2);
            document.getElementById('modal-classes').textContent = data.classes;
            document.getElementById('modal-phone').textContent = data.phone;
            document.getElementById('modal-email').textContent = data.email;
            document.getElementById('modal-address').textContent = data.address;
            
            // Status Badge Logic
            const badge = document.getElementById('modal-status-badge');
            badge.textContent = data.status;
            if (data.status === 'ACTIVE') {
                badge.className = 'inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-bold';
            } else {
                badge.className = 'inline-flex items-center px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold';
            }

            // Update Progress Link
            const progressLink = document.getElementById('modal-progress-link');
            progressLink.href = `/teacher/student-progress/input?student_id=${data.id}`;

            // Show Modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            if (window.lucide) window.lucide.createIcons();
        }

        function closeStudentModal() {
            const modal = document.getElementById('studentDetailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection

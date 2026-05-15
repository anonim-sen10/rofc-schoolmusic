@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard'), 'key' => 'dashboard'],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index'), 'key' => 'my_classes'],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index'), 'key' => 'my_schedule'],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index'), 'key' => 'student_progress'],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index'), 'key' => 'my_students'],
        ['label' => 'Materials', 'url' => route('teacher.materials.index'), 'key' => 'materials'],
        ['label' => 'Profile', 'url' => route('teacher.profile.index'), 'key' => 'profile'],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp

@extends('portal.layouts.app')

@section('title', 'My Classes')
@section('page-title', 'My Classes')
@section('page-subtitle', 'View and manage music classes assigned to you.')

@section('content')
    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div>
                <h3 class="text-base font-bold text-slate-900 leading-none">Active Classes</h3>
                <p class="text-[11px] font-medium text-slate-400 mt-1">Daftar kelas musik yang Anda ajar.</p>
            </div>
            <i data-lucide="book-open" class="w-4 h-4 text-slate-300"></i>
        </div>

        <div class="table-wrap">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Class Name</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Description</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Students</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Schedule</th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($classes as $class)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4" data-label="Class Name">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                                        <i data-lucide="music-2" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $class->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4" data-label="Description">
                                <span class="text-xs text-slate-500 truncate block max-w-[200px]">{{ $class->description ?: '-' }}</span>
                            </td>
                            <td class="px-6 py-4" data-label="Students">
                                <button type="button" 
                                    onclick="showStudentModal('{{ $class->id }}', '{{ $class->name }}')"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 text-[10px] font-bold hover:bg-white hover:border-blue-400 hover:text-blue-600 transition-all">
                                    <i data-lucide="users" class="w-3 h-3"></i>
                                    <span>{{ $class->students_count ?? 0 }} Student</span>
                                </button>
                            </td>
                            <td class="px-6 py-4" data-label="Schedule">
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                    <span>{{ $class->schedules_count ?? 0 }} Slot</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right" data-label="Status">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $class->status === 'active' ? 'bg-green-50 text-green-600' : 'bg-slate-50 text-slate-400' }} text-[9px] font-bold border border-current opacity-80">
                                    {{ strtoupper($class->status ?: 'inactive') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-14 w-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200 mb-3">
                                        <i data-lucide="folder-open" class="w-6 h-6"></i>
                                    </div>
                                    <h4 class="text-slate-900 font-bold text-sm">No classes found</h4>
                                    <p class="text-slate-400 text-xs">You are not assigned to any classes yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- Modern Student List Modal (Consistent with Student Detail) --}}
    <div id="premiumStudentModal" class="hidden items-center justify-center overflow-hidden px-4 transition-all duration-300" style="position: fixed !important; inset: 0 !important; z-index: 999999 !important;">
        {{-- Fullscreen Overlay --}}
        <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-[2px] transition-opacity" onclick="hideStudentModal()" style="position: absolute !important; inset: 0 !important;"></div>
        
        {{-- Modal Container (Compact) --}}
        <div class="relative w-full max-w-md transform overflow-hidden rounded-[28px] bg-white shadow-[0_25px_80px_-15px_rgba(0,0,0,0.4)] transition-all animate-in fade-in zoom-in slide-in-from-bottom-8 duration-500 ease-out">
            {{-- Decorative Background --}}
            <div class="absolute top-0 left-0 right-0 h-24 bg-gradient-to-br from-blue-600/5 via-blue-50/20 to-transparent"></div>

            <header class="relative px-8 pt-8 pb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white font-black text-lg shadow-lg shadow-blue-100 uppercase transition-transform duration-300">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 id="modalClassName" class="text-lg font-black text-slate-900 tracking-tight leading-tight">Student List</h3>
                            <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-widest">Daftar siswa dalam kelas ini</p>
                        </div>
                    </div>
                    <button type="button" onclick="hideStudentModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-white border border-slate-100 text-slate-400 shadow-sm transition-all hover:bg-red-50 hover:text-red-500 hover:rotate-90">
                        <i data-lucide="x" class="h-4 w-4"></i>
                    </button>
                </div>
            </header>

            <div class="px-8 pb-8">
                <div class="max-h-[50vh] overflow-y-auto pr-1 -mr-1 custom-scrollbar" id="modalStudentList">
                    <!-- Students injected here -->
                </div>

                {{-- Footer Actions --}}
                <div class="pt-6 flex justify-end">
                    <button type="button" onclick="hideStudentModal()" class="px-8 py-3.5 rounded-2xl bg-slate-50 text-slate-500 text-[11px] font-bold transition-all hover:bg-slate-100 uppercase tracking-widest">
                        CLOSE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const classRecords = @json($classes);

        function showStudentModal(classId, className) {
            const modal = document.getElementById('premiumStudentModal');
            const listContainer = document.getElementById('modalStudentList');
            const titleEl = document.getElementById('modalClassName');

            titleEl.innerText = className;
            listContainer.innerHTML = '';

            const currentClass = classRecords.find(c => c.id == classId);
            if (!currentClass || !currentClass.schedules) return;

            // Get unique students
            const studentsSet = new Map();
            currentClass.schedules.forEach(s => {
                if (s.student && !studentsSet.has(s.student.id)) {
                    studentsSet.set(s.student.id, {
                        ...s.student,
                        day: s.day,
                        time: s.time
                    });
                }
            });

            const studentList = Array.from(studentsSet.values());

            if (studentList.length === 0) {
                listContainer.innerHTML = '<div class="text-center py-8 text-slate-400 text-xs font-medium">No students in this class</div>';
            } else {
                studentList.forEach(student => {
                    const initial = student.name.substring(0, 2).toUpperCase();
                    listContainer.innerHTML += `
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-100 mb-3 last:mb-0 hover:shadow-md hover:border-blue-100 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 shrink-0 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-[11px] font-black text-slate-400 uppercase group-hover:bg-blue-600 group-hover:text-white transition-all">${initial}</div>
                                <div>
                                    <p class="text-sm font-black text-slate-800 leading-none mb-1.5">${student.name}</p>
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-1.5 rounded-full bg-blue-400"></div>
                                        <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest">${student.day || '-'} • ${student.time ? student.time.substring(0, 5) : '-'}</p>
                                    </div>
                                </div>
                            </div>
                            <a href="/teacher/student-progress/input?student_id=${student.id}" class="h-9 w-9 shrink-0 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center transition-all hover:bg-slate-900 hover:text-white">
                                <i data-lucide="pencil-line" class="w-4 h-4"></i>
                            </a>
                        </div>
                    `;
                });
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            if (window.lucide) window.lucide.createIcons();
        }

        function hideStudentModal() {
            const modal = document.getElementById('premiumStudentModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection

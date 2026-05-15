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

        <div class="overflow-x-auto">
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
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                                        <i data-lucide="music-2" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $class->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-slate-500 truncate block max-w-[200px]">{{ $class->description ?: '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" 
                                    onclick="showStudentModal('{{ $class->id }}', '{{ $class->name }}')"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-600 text-[10px] font-bold hover:bg-white hover:border-blue-400 hover:text-blue-600 transition-all">
                                    <i data-lucide="users" class="w-3 h-3"></i>
                                    <span>{{ $class->students_count ?? 0 }} Student</span>
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                    <span>{{ $class->schedules_count ?? 0 }} Slot</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
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

    {{-- Student List Modal --}}
    <div id="premiumStudentModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="hideStudentModal()"></div>
        <div class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white shadow-2xl transition-all animate-in fade-in zoom-in duration-300">
            <header class="border-b border-slate-100 bg-white px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 id="modalClassName" class="text-sm font-bold text-slate-900 leading-none">Student List</h3>
                        <p class="text-[10px] text-slate-400 mt-1">Daftar siswa dalam kelas ini</p>
                    </div>
                </div>
                <button type="button" onclick="hideStudentModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </header>
            <div class="p-6 max-h-[60vh] overflow-y-auto" id="modalStudentList">
                <!-- Students injected here -->
            </div>
            <footer class="bg-slate-50 px-6 py-4 flex justify-end">
                <button type="button" onclick="hideStudentModal()" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-[11px] font-bold text-slate-600 hover:bg-slate-50">TUTUP</button>
            </footer>
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
                    const initial = student.name.charAt(0).toUpperCase();
                    listContainer.innerHTML += `
                        <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 bg-slate-50/50 mb-2 last:mb-0 hover:bg-white hover:border-blue-100 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-white border border-slate-100 flex items-center justify-center text-[11px] font-bold text-slate-500 uppercase">${initial}</div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900 leading-none">${student.name}</p>
                                    <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-wider">${student.day || '-'} ${student.time ? student.time.substring(0, 5) : '-'}</p>
                                </div>
                            </div>
                            <a href="/teacher/student-progress/input?student_id=${student.id}" class="h-8 w-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:border-blue-100 transition-all">
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

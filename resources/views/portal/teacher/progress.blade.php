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

@section('title', 'Student Progress')
@section('page-title', 'Student Progress Notes')
@section('page-subtitle', 'Catat dan pantau perkembangan belajar musik siswa Anda.')

@section('content')
@php($hasClasses = is_countable($classes) && count($classes) > 0)
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-2">
    {{-- Input Form Card --}}
    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-fit">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div>
                <h3 class="text-base font-bold text-slate-900 leading-none">Input Progress</h3>
                <p class="text-[11px] font-medium text-slate-400 mt-1">Catat pencapaian siswa hari ini.</p>
            </div>
            <i data-lucide="line-chart" class="w-4 h-4 text-slate-300"></i>
        </div>

        <form class="p-6 flex flex-col gap-4" method="POST" action="{{ route('teacher.student-progress.store') }}">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Class</label>
                <select name="class_id" id="progress-class-id" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                    @forelse($classes as $class)
                        <option value="{{ $class->id }}" @selected(old('class_id', $selectedClassId) == $class->id)>
                            {{ $class->name }}
                        </option>
                    @empty
                        <option value="">Belum ada kelas</option>
                    @endforelse
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Student</label>
                <select name="student_id" id="progress-student-id" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                    <option value="">Pilih siswa</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Topic</label>
                    <input type="text" name="topic" value="{{ old('topic') }}" placeholder="Materi..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Score</label>
                    <input type="text" name="score" value="{{ old('score') }}" placeholder="0-100" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Recorded At</label>
                <input type="date" name="recorded_at" value="{{ old('recorded_at', now()->format('Y-m-d')) }}" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Note</label>
                <textarea name="note" rows="4" placeholder="Detail perkembangan..." class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">{{ old('note') }}</textarea>
            </div>

            <button type="submit" @disabled(! $hasClasses) class="w-full py-3.5 rounded-2xl bg-blue-600 text-white text-xs font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-2 mt-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i> SIMPAN PROGRESS
            </button>
        </form>
    </section>

    {{-- Recent Progress Card --}}
    <section class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div>
                <h3 class="text-base font-bold text-slate-900 leading-none">Recent Progress</h3>
                <p class="text-[11px] font-medium text-slate-400 mt-1">Catatan perkembangan terbaru.</p>
            </div>
            <i data-lucide="history" class="w-4 h-4 text-slate-300"></i>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Topic</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Student</th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Score</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($records as $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold text-slate-700">{{ optional($row->recorded_at)->format('d M Y') }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-medium text-slate-600">{{ $row->topic ?: '-' }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center text-[9px] font-bold text-slate-500 uppercase border border-slate-200">
                                        {{ substr($row->student->name ?? 'S', 0, 2) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">{{ $row->student->name ?? 'ID: '.$row->student_id }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-bold border border-blue-100">
                                    {{ $row->score ?: '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-4">
                                        <i data-lucide="bar-chart-2" class="w-8 h-8"></i>
                                    </div>
                                    <h4 class="text-slate-900 font-bold text-sm">Belum ada progress</h4>
                                    <p class="text-slate-400 text-xs">Belum ada catatan perkembangan siswa.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const classStudents = @json($classStudents);
    const classSelect = document.getElementById('progress-class-id');
    const studentSelect = document.getElementById('progress-student-id');
    const selectedStudentId = @json((int) old('student_id', $selectedStudentId ?? 0));

    if (!classSelect || !studentSelect) {
        return;
    }

    const renderStudentOptions = (classId) => {
        const students = classStudents[classId] ?? [];
        studentSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = students.length ? 'Pilih siswa' : 'Belum ada siswa di kelas ini';
        studentSelect.appendChild(placeholder);

        students.forEach((student) => {
            const option = document.createElement('option');
            option.value = student.id;
            option.textContent = student.name;
            option.selected = Number(selectedStudentId) === Number(student.id);
            studentSelect.appendChild(option);
        });
    };

    renderStudentOptions(classSelect.value);
    classSelect.addEventListener('change', () => renderStudentOptions(classSelect.value));
});
</script>
@endsection
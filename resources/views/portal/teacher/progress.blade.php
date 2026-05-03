@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index')],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index')],
        ['label' => 'Attendance', 'url' => route('teacher.attendance.index')],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index')],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index')],
        ['label' => 'Materials', 'url' => route('teacher.materials.index')],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title','Student Progress')
@section('page-title','Student Progress Notes')
@section('content')
@php($hasClasses = is_countable($classes) && count($classes) > 0)
<div class="split-grid">
	<section class="card">
		<h3>Input Progress</h3>

		<form class="module-form" method="POST" action="{{ route('teacher.student-progress.store') }}">
			@csrf

			<label>
				Class
				<select name="class_id" id="progress-class-id" required>
					@forelse($classes as $class)
						<option value="{{ $class->id }}" @selected(old('class_id', $selectedClassId) == $class->id)>
							{{ $class->name }}
						</option>
					@empty
						<option value="">Belum ada kelas</option>
					@endforelse
				</select>
			</label>

			<label>
				Student
				<select name="student_id" id="progress-student-id" required>
					<option value="">Pilih siswa</option>
				</select>
			</label>

			<label>Topic<input type="text" name="topic" value="{{ old('topic') }}"></label>
			<label>Score<input type="text" name="score" value="{{ old('score') }}"></label>
			<label>Recorded At<input type="date" name="recorded_at" value="{{ old('recorded_at', now()->format('Y-m-d')) }}"></label>
			<label>Note<textarea name="note">{{ old('note') }}</textarea></label>
			<button type="submit" @disabled(! $hasClasses)>Simpan</button>
		</form>
	</section>

	<section class="card">
		<h3>Recent Progress</h3>

		<div class="table-wrap">
			<table>
				<thead>
					<tr>
						<th>Date</th>
						<th>Topic</th>
						<th>Student ID</th>
						<th>Score</th>
					</tr>
				</thead>
				<tbody>
					@forelse($records as $row)
						<tr>
							<td>{{ optional($row->recorded_at)->format('Y-m-d') }}</td>
							<td>{{ $row->topic ?: '-' }}</td>
							<td>{{ $row->student_id }}</td>
							<td>{{ $row->score ?: '-' }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="4">Belum ada progress.</td>
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
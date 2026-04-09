@php $menuItems=[['label'=>'Dashboard','url'=>route('teacher.dashboard')],['label'=>'Attendance','url'=>route('teacher.attendance.index')],['label'=>'Student Progress','url'=>route('teacher.student-progress.index')],['label'=>'Materials','url'=>route('teacher.materials.index')],['label'=>'My Classes','url'=>route('teacher.my-classes.index')],['label'=>'My Students','url'=>route('teacher.my-students.index')],['label'=>'Schedule','url'=>route('teacher.schedule.index')]]; $panelTitle='Teacher Portal'; $homeRoute=route('teacher.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Attendance')
@section('page-title','Attendance Input')
@section('content')
<div class="split-grid">
<section class="card"><h3>Absen Guru (Diri Sendiri)</h3><form class="module-form" method="POST" action="{{ route('teacher.attendance.teacher.store') }}">@csrf
<label>Nama Guru<input type="text" value="{{ $teacher->name }}" readonly></label>
<label>Tanggal<input type="date" name="attendance_date" value="{{ now()->format('Y-m-d') }}" required></label>
<label>Status<select name="status"><option value="present">present</option><option value="absent">absent</option><option value="late">late</option></select></label>
<label>Live Location
	<input type="text" id="teacher-location-text" name="location_text" placeholder="Belum ada lokasi" readonly>
	<input type="hidden" id="teacher-latitude" name="latitude">
	<input type="hidden" id="teacher-longitude" name="longitude">
	<button type="button" id="btn-get-location">Ambil Lokasi Live</button>
</label>
<label>Note<textarea name="note"></textarea></label>
<button type="submit">Simpan Absen Guru</button></form>
</section>
<section class="card"><h3>Absen Siswa</h3>
@if($hasTeacherAttendanceToday)
@if($hasAssignedClasses)
<form class="module-form" method="POST" action="{{ route('teacher.attendance.store') }}">@csrf
<label>Kelas
	<select name="class_id" id="class-id-select" required>
		@foreach($classOptions as $musicClass)
			<option value="{{ $musicClass->id }}" @selected(old('class_id') == $musicClass->id)>{{ $musicClass->name }}</option>
		@endforeach
	</select>
</label>
<label>Nama Siswa
	<select name="student_id" id="student-id-select" required>
		<option value="">Pilih siswa</option>
	</select>
</label>
<label>Tanggal<input type="date" name="attendance_date" value="{{ now()->format('Y-m-d') }}" required></label>
<label>Status<select name="status"><option value="present">present</option><option value="absent">absent</option><option value="late">late</option></select></label>
<label>Note<textarea name="note"></textarea></label>
<button type="submit">Simpan Absen Siswa</button></form>
@else
<p>Dropdown kelas kosong karena guru ini belum di-assign ke kelas manapun. Minta Admin atau Super Admin assign kelas terlebih dahulu.</p>
@endif
@else
<p>Absen siswa akan muncul setelah guru melakukan absen diri sendiri hari ini.</p>
@endif
</section>
</div>

<div class="split-grid">
<section class="card"><h3>Riwayat Absen Guru</h3><div class="table-wrap"><table><thead><tr><th>Tanggal</th><th>Nama Guru</th><th>Status</th><th>Lokasi</th></tr></thead><tbody>@foreach($teacherRecords as $row)<tr><td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td><td>{{ $row->teacher?->name }}</td><td>{{ $row->status }}</td><td>{{ $row->location_text ?? '-' }}</td></tr>@endforeach</tbody></table></div></section>
<section class="card"><h3>Riwayat Absen Siswa</h3><div class="table-wrap"><table><thead><tr><th>Tanggal</th><th>Kelas</th><th>Nama Siswa</th><th>Status</th></tr></thead><tbody>@foreach($records as $row)<tr><td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td><td>{{ $row->class?->name }}</td><td>{{ $row->student?->name }}</td><td>{{ $row->status }}</td></tr>@endforeach</tbody></table></div></section>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
	const classStudents = @json($classStudents);
	const classSelect = document.getElementById('class-id-select');
	const studentSelect = document.getElementById('student-id-select');
	const oldStudentId = @json(old('student_id'));

	const renderStudentOptions = (classId) => {
		if (!studentSelect) {
			return;
		}

		const students = classStudents[classId] ?? [];
		studentSelect.innerHTML = '';

		const placeholderOption = document.createElement('option');
		placeholderOption.value = '';
		placeholderOption.textContent = students.length ? 'Pilih siswa' : 'Belum ada siswa di kelas ini';
		studentSelect.appendChild(placeholderOption);

		students.forEach((student) => {
			const option = document.createElement('option');
			option.value = student.id;
			option.textContent = student.name;
			if (oldStudentId && Number(oldStudentId) === Number(student.id)) {
				option.selected = true;
			}
			studentSelect.appendChild(option);
		});
	};

	if (classSelect) {
		renderStudentOptions(classSelect.value);
		classSelect.addEventListener('change', () => renderStudentOptions(classSelect.value));
	}

	const button = document.getElementById('btn-get-location');
	const textInput = document.getElementById('teacher-location-text');
	const latInput = document.getElementById('teacher-latitude');
	const lngInput = document.getElementById('teacher-longitude');

	if (!button || !textInput || !latInput || !lngInput) {
		return;
	}

	button.addEventListener('click', () => {
		if (!navigator.geolocation) {
			textInput.value = 'Browser tidak mendukung geolocation';
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
			},
			() => {
				textInput.value = 'Gagal mengambil lokasi';
			}
		);
	});
});
</script>
@endsection
    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .summary-box {
            margin-bottom: 20px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }
        .summary-box table {
            width: 100%;
            border: none;
        }
        .summary-box td {
            padding: 4px;
            border: none;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }
        table.data-table th {
            background-color: #eee;
            font-weight: bold;
        }
        .text-center {
            text-align: center !important;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
        }
        .badge-present { background-color: #15803d; }
        .badge-absent { background-color: #b91c1c; }
        .badge-reschedule { background-color: #c2410c; }
    </style>
</head>
<body>

    <div class="header">
        <h2>REKAPITULASI ABSENSI KELAS MUSIC</h2>
        <p>Rofc Music School</p>
    </div>

    <div class="summary-box">
        <table>
            <tr>
                <td width="15%"><strong>Filter Waktu</strong></td>
                <td width="35%">: {{ $date ? \Carbon\Carbon::parse($date)->format('d F Y') : ($month ?? false ? \Carbon\Carbon::parse($month . '-01')->format('F Y') : 'Semua Waktu') }}</td>
                <td width="15%"><strong>Total Data</strong></td>
                <td width="35%">: {{ $totalCount }} Sesi</td>
            </tr>
            <tr>
                <td><strong>Guru Difilter</strong></td>
                <td>: {{ $teacher ? $teacher->name : 'Semua Guru' }}</td>
                <td><strong>Kelas Difilter</strong></td>
                <td>: {{ $class ? $class->name : 'Semua Kelas' }}</td>
            </tr>
        </table>
    </div>

    @php
        $groupedAttendances = $attendances->groupBy('teacher_id');
        $daysMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
    @endphp

    @forelse($groupedAttendances as $teacherId => $teacherAttendances)
        @php
            $teacherName = $teacherAttendances->first()->teacher->name ?? 'Guru Tidak Diketahui';
            
            // Jumlah siswa unik yang ada di rekap absen ini
            $uniqueStudents = $teacherAttendances->pluck('student_id')->unique()->count();
            
            // Jadwal mengajar setiap hari (mengambil hari dari tanggal absen)
            $dayNames = $teacherAttendances->map(function($att) use ($daysMap) {
                return $daysMap[$att->created_at->format('l')] ?? '';
            })->unique()->implode(', ');
            
            // Total hadir per siswa
            $presentAttendances = $teacherAttendances->where('status', 'present');
            $studentsPresentCount = $presentAttendances->groupBy('student_id')->map(function($studentGroup) {
                $studentName = $studentGroup->first()->student->name ?? 'Unknown';
                return $studentName . ' (' . $studentGroup->count() . 'x)';
            })->implode(', ');
        @endphp

        <div style="margin-top: 30px; page-break-inside: avoid;">
            <h3 style="margin-bottom: 8px; color: #1e3a8a; border-bottom: 1px solid #ccc; padding-bottom: 4px;">{{ $teacherName }}</h3>
            <div style="margin-bottom: 10px; font-size: 11px;">
                <table style="width: 100%; border: none;">
                    <tr>
                        <td width="25%" style="border: none; padding: 2px;"><strong>Jumlah Siswa</strong></td>
                        <td width="75%" style="border: none; padding: 2px;">: {{ $uniqueStudents }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px;"><strong>Jadwal Mengajar</strong></td>
                        <td style="border: none; padding: 2px;">: {{ $dayNames ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 2px; vertical-align: top;"><strong>Total Hadir Siswa</strong></td>
                        <td style="border: none; padding: 2px;">: {{ $studentsPresentCount ?: 'Belum ada yang hadir' }}</td>
                    </tr>
                </table>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="12%">Tgl Sesi</th>
                        <th width="8%">Jam</th>
                        <th width="20%">Siswa</th>
                        <th width="15%">Kelas</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="30%">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teacherAttendances as $index => $att)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $att->created_at->format('d M Y') }}</td>
                            <td>{{ $att->schedule ? \Carbon\Carbon::parse($att->schedule->time)->format('H:i') : '-' }}</td>
                            <td>{{ $att->student->name ?? '-' }}</td>
                            <td>{{ $att->class->name ?? '-' }}</td>
                            <td class="text-center">
                                @php $status = strtolower($att->status); @endphp
                                <span class="badge badge-{{ $status }}">{{ $status }}</span>
                            </td>
                            <td>{{ $att->note ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 30px; border: 1px dashed #ccc; margin-top: 20px;">
            Tidak ada data absensi pada filter ini.
        </div>
    @endforelse

</body>
</html>

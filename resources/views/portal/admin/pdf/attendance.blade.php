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
                <td width="15%"><strong>Filter Tanggal</strong></td>
                <td width="35%">: {{ $date ? \Carbon\Carbon::parse($date)->format('d F Y') : 'Semua Waktu' }}</td>
                <td width="15%"><strong>Total Data</strong></td>
                <td width="35%">: {{ $totalCount }} Sesi</td>
            </tr>
            <tr>
                <td><strong>Guru</strong></td>
                <td>: {{ $teacher ? $teacher->name : 'Semua Guru' }}</td>
                <td><strong>Hadir</strong></td>
                <td>: {{ $presentCount }} Sesi</td>
            </tr>
            <tr>
                <td><strong>Kelas</strong></td>
                <td>: {{ $class ? $class->name : 'Semua Kelas' }}</td>
                <td><strong>Alpa/Reschedule</strong></td>
                <td>: {{ $absentCount }} / {{ $rescCount }} Sesi</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Tgl Sesi</th>
                <th width="8%">Jam</th>
                <th width="15%">Guru</th>
                <th width="15%">Siswa</th>
                <th width="12%">Kelas</th>
                <th width="10%" class="text-center">Status</th>
                <th width="23%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $att)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $att->created_at->format('d M Y') }}</td>
                    <td>{{ $att->schedule ? \Carbon\Carbon::parse($att->schedule->time)->format('H:i') : '-' }}</td>
                    <td>{{ $att->teacher->name ?? '-' }}</td>
                    <td>{{ $att->student->name ?? '-' }}</td>
                    <td>{{ $att->class->name ?? '-' }}</td>
                    <td class="text-center">
                        @php $status = strtolower($att->status); @endphp
                        <span class="badge badge-{{ $status }}">{{ $status }}</span>
                    </td>
                    <td>{{ $att->note ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data absensi pada filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

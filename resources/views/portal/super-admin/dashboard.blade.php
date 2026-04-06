@extends('portal.layout')

@section('title', 'Super Admin Dashboard | ROFC')
@section('page-title', 'Super Admin Data Center')

@section('content')
<section class="stats-grid">
    @foreach ($stats as $stat)
        <article class="card stat">
            <p>{{ $stat['label'] }}</p>
            <h2>{{ $stat['value'] }}</h2>
        </article>
    @endforeach
</section>

<section class="split-grid">
    <article class="card">
        <h3>Cross-Role Snapshot</h3>
        <ul class="list">
            <li><span>Registrations Pending</span><small>{{ $summary['registrations_pending'] }}</small></li>
            <li><span>Invoices Unpaid</span><small>{{ $summary['invoices_unpaid'] }}</small></li>
            <li><span>Teacher Attendance Today</span><small>{{ $summary['teacher_attendance_today'] }}</small></li>
            <li><span>Student Attendance Today</span><small>{{ $summary['student_attendance_today'] }}</small></li>
            <li><span>Progress Updates Today</span><small>{{ $summary['progress_updates_today'] }}</small></li>
            <li><span>Materials Uploaded</span><small>{{ $summary['materials_uploaded'] }}</small></li>
        </ul>
    </article>
    <article class="card">
        <h3>Quick Access</h3>
        <div class="quick-actions">
            <a href="{{ route('super-admin.module', ['module' => 'users']) }}">User Management</a>
            <a href="{{ route('super-admin.module', ['module' => 'registrations']) }}">Registrations</a>
            <a href="{{ route('super-admin.module', ['module' => 'finance']) }}">Finance Summary</a>
            <a href="{{ route('super-admin.module', ['module' => 'reports']) }}">Cross Reports</a>
        </div>
    </article>
</section>

<section class="split-grid">
    <article class="card">
        <h3>Latest Registrations (Admin)</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Name</th><th>Email</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($recentRegistrations as $row)
                        <tr>
                            <td>{{ $row->full_name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ strtoupper($row->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
    <article class="card">
        <h3>Latest Payments (Finance)</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Date</th><th>Student</th><th>Amount</th></tr>
                </thead>
                <tbody>
                    @forelse ($recentPayments as $row)
                        <tr>
                            <td>{{ optional($row->paid_at)->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $row->student?->name ?? '-' }}</td>
                            <td>Rp{{ number_format($row->amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>

<section class="split-grid">
    <article class="card">
        <h3>Teacher Attendance (Teacher)</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Date</th><th>Teacher</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($recentTeacherAttendances as $row)
                        <tr>
                            <td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td>
                            <td>{{ $row->teacher?->name ?? '-' }}</td>
                            <td>{{ strtoupper($row->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
    <article class="card">
        <h3>Student Attendance (Teacher)</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Date</th><th>Class</th><th>Student</th></tr>
                </thead>
                <tbody>
                    @forelse ($recentStudentAttendances as $row)
                        <tr>
                            <td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td>
                            <td>{{ $row->class?->name ?? '-' }}</td>
                            <td>{{ $row->student?->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>

<section class="card">
    <h3>Latest Progress (Teacher -> Student)</h3>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Recorded</th><th>Student ID</th><th>Class ID</th><th>Topic</th><th>Score</th></tr>
            </thead>
            <tbody>
                @forelse ($recentProgress as $row)
                    <tr>
                        <td>{{ optional($row->recorded_at)->format('Y-m-d') ?? optional($row->created_at)->format('Y-m-d') }}</td>
                        <td>{{ $row->student_id }}</td>
                        <td>{{ $row->class_id }}</td>
                        <td>{{ $row->topic ?? '-' }}</td>
                        <td>{{ $row->score ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

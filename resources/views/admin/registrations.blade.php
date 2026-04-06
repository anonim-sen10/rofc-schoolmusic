@extends('admin.layout')

@section('title', 'Registrations | ROFC Admin')
@section('page-title', 'Registrations Management')

@section('content')
<section class="admin-card">
    <h2>Daftar Pendaftaran Siswa</h2>
    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Program</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registrations as $registration)
                    <tr>
                        <td>{{ $registration['name'] }}</td>
                        <td>{{ $registration['program'] }}</td>
                        <td>{{ $registration['schedule'] }}</td>
                        <td><span class="status {{ $registration['status'] }}">{{ ucfirst($registration['status']) }}</span></td>
                        <td>
                            <div class="action-row">
                                <button class="btn-admin" type="button">Approve</button>
                                <button class="btn-admin ghost" type="button">Reject</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection

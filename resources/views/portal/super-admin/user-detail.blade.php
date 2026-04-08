@extends('portal.layout')

@section('title', 'Detail User | ROFC')
@section('page-title', 'Detail User')

@section('content')
<section class="card module-head">
    <h2>{{ $user->name }}</h2>
    <p>Detail akun user dan role aktif.</p>
</section>

<section class="card">
    <div class="table-wrap">
        <table>
            <tbody>
                <tr>
                    <th>Nama</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>{{ $user->roles->pluck('slug')->implode(', ') ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Dibuat</th>
                    <td>{{ optional($user->created_at)->format('Y-m-d H:i') }}</td>
                </tr>
                <tr>
                    <th>Diupdate</th>
                    <td>{{ optional($user->updated_at)->format('Y-m-d H:i') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1rem; display: flex; gap: 0.6rem;">
        <a href="{{ route('super-admin.users.edit', $user) }}" class="logout-btn">Edit</a>
        <a href="{{ route('super-admin.module', ['module' => 'roles']) }}" class="logout-btn">Kembali</a>
    </div>
</section>
@endsection

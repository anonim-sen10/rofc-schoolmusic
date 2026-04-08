<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Role Custom | ROFC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/portal.css'])
</head>
<body class="portal-auth-body">
    <main class="login-shell">
        <section class="login-panel">
            <p class="badge">ROFC School Music</p>
            <h1>Portal Untuk Role Custom</h1>
            <p>Akun Anda berhasil login dengan role <strong>{{ strtoupper(str_replace('_', ' ', $roleKey)) }}</strong>.</p>
            <p>Role ini belum dipetakan ke dashboard khusus. Silakan hubungi Super Admin untuk konfigurasi menu dan izin akses.</p>

            <form action="{{ route('logout') }}" method="POST" style="margin-top: 1rem;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </section>
    </main>
</body>
</html>

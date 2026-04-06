<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | ROFC Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/portal.css'])
</head>
<body class="portal-auth-body">
    <main class="login-shell">
        <section class="login-panel">
            <p class="badge">ROFC School Music</p>
            <h1>Music School Management System</h1>
            <p>Login untuk mengakses dashboard sesuai role: Super Admin, Admin, Finance, Teacher, Student.</p>

            @if ($errors->any())
                <div class="form-error">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" class="login-form">
                @csrf
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label class="remember">
                    <input type="checkbox" name="remember" value="1"> Remember me
                </label>
                <button type="submit">Masuk</button>
            </form>

            <small class="hint">Demo credential akan tersedia setelah menjalankan seeder.</small>
        </section>
    </main>
</body>
</html>

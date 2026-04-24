<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SchoolMusic</title>
    <meta name="description" content="SchoolMusic login for modern music school management.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/portal.css'])
    <style>
        .auth-page {
            font-family: "Outfit", "Inter", sans-serif;
            margin: 0;
            background: #0f3ea8;
        }

        .auth-gradient {
            background: #0f3ea8;
        }

        .float-note {
            animation: note-float 6s ease-in-out infinite;
        }

        .float-note.alt {
            animation-delay: 1.2s;
            animation-duration: 7.2s;
        }

        .float-note.alt-2 {
            animation-delay: 2.1s;
            animation-duration: 5.8s;
        }

        .line-wave {
            position: absolute;
            width: 20rem;
            height: 20rem;
            border-radius: 9999px;
            border: 1px solid rgba(224, 231, 255, 0.16);
            filter: blur(0.6px);
        }

        .line-wave::before,
        .line-wave::after {
            content: "";
            position: absolute;
            inset: 1rem;
            border-radius: 9999px;
            border: 1px solid rgba(224, 231, 255, 0.12);
        }

        .line-wave::after {
            inset: 2rem;
            border-color: rgba(224, 231, 255, 0.1);
        }

        .input-glow:focus {
            box-shadow:
                0 0 0 4px rgba(99, 102, 241, 0.2),
                0 10px 24px rgba(30, 64, 175, 0.2);
            transform: translateY(-1px);
        }

        .cta-shine {
            position: relative;
            overflow: hidden;
        }

        .cta-shine::after {
            content: "";
            position: absolute;
            top: 0;
            left: -70%;
            width: 45%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transform: skewX(-18deg);
            transition: left 0.55s ease;
        }

        .cta-shine:hover::after {
            left: 130%;
        }

        @keyframes note-float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.55;
            }

            50% {
                transform: translateY(-11px) rotate(4deg);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="auth-page min-h-screen text-slate-100 antialiased">
    <main class="auth-gradient relative isolate min-h-screen overflow-hidden px-4 py-7 sm:px-6 sm:py-10">
        <div class="pointer-events-none absolute inset-0">
            <div class="line-wave -left-32 top-[-7rem]"></div>
            <div class="line-wave alt right-[-9rem] top-[35%]"></div>
            <span class="float-note absolute left-[9%] top-[13%] text-3xl text-indigo-100/65">♪</span>
            <span class="float-note alt absolute right-[13%] top-[24%] text-4xl text-indigo-100/65">♫</span>
            <span class="float-note alt-2 absolute left-[7%] bottom-[21%] text-3xl text-indigo-100/65">♬</span>
            <span class="float-note absolute right-[9%] bottom-[15%] text-3xl text-indigo-100/65">♩</span>
        </div>

        <div class="relative mx-auto flex min-h-[calc(100vh-3.5rem)] w-full max-w-[30rem] items-center justify-center sm:min-h-[calc(100vh-5rem)]">
            <section class="w-full rounded-3xl border border-white/30 bg-white/12 p-5 shadow-[0_25px_80px_rgba(2,6,23,0.4)] backdrop-blur-xl sm:p-8">
                <div class="mb-6 text-center sm:mb-7">
                    <p class="mx-auto mb-3 inline-flex rounded-full border border-white/35 bg-white/15 px-3 py-1 text-xs font-semibold tracking-[0.09em] text-indigo-100">
                        SCHOOLMUSIC
                    </p>
                    <h1 class="text-2xl font-bold leading-tight text-white sm:text-[1.9rem]">Welcome Back</h1>
                    <p class="mt-2 text-sm leading-relaxed text-indigo-100/90">
                        Sign in to continue managing your classes, students, and music programs.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-rose-300/40 bg-rose-500/15 px-3 py-2 text-sm text-rose-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="email" class="text-sm font-medium text-indigo-50">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            class="input-glow h-11 w-full rounded-xl border border-white/30 bg-white/90 px-4 text-sm text-slate-900 outline-none transition duration-200 placeholder:text-slate-500"
                            placeholder="you@schoolmusic.com"
                        >
                    </div>

                    <div class="space-y-1.5">
                        <label for="login-password" class="text-sm font-medium text-indigo-50">Password</label>
                        <div class="relative">
                            <input
                                id="login-password"
                                type="password"
                                name="password"
                                autocomplete="current-password"
                                required
                                class="input-glow h-11 w-full rounded-xl border border-white/30 bg-white/90 px-4 pr-24 text-sm text-slate-900 outline-none transition duration-200 placeholder:text-slate-500"
                                placeholder="Enter your password"
                            >
                            <button
                                type="button"
                                id="toggle-login-password"
                                aria-controls="login-password"
                                aria-label="Show password"
                                class="absolute inset-y-1.5 right-1.5 inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 text-slate-600 transition hover:border-indigo-300 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/35"
                            >
                                <svg id="eye-open-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="m3 3 18 18" />
                                    <path d="M10.58 10.58A2 2 0 0 0 12 14a2 2 0 0 0 1.42-.58" />
                                    <path d="M9.88 5.09A10.94 10.94 0 0 1 12 4.9c5.05 0 9.27 3.11 10.67 7.1a1.2 1.2 0 0 1 0 .8 11 11 0 0 1-4.23 5.41" />
                                    <path d="M6.61 6.61A11 11 0 0 0 1.33 12a1.2 1.2 0 0 0 0 .8c1.4 4 5.62 7.1 10.67 7.1a10.94 10.94 0 0 0 4.11-.8" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-indigo-100/95">
                            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-white/40 text-indigo-600 focus:ring-indigo-500/40">
                            Remember me
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-100 transition hover:text-white focus:outline-none focus:underline">
                                Forgot password?
                            </a>
                        @else
                            <span class="text-sm text-indigo-100/80">Need help?</span>
                        @endif
                    </div>

                    <button
                        type="submit"
                        class="cta-shine inline-flex h-11 w-full items-center justify-center rounded-xl bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-400 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(37,99,235,0.36)] transition duration-250 hover:-translate-y-0.5 hover:shadow-[0_18px_36px_rgba(37,99,235,0.5)] focus:outline-none focus:ring-4 focus:ring-indigo-300/35"
                    >
                        Sign In
                    </button>
                </form>

                <p class="mt-5 text-center text-xs text-indigo-100/85">
                    Demo credentials are available after running seeder.
                </p>
            </section>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('login-password');
            const toggleButton = document.getElementById('toggle-login-password');
            const eyeOpenIcon = document.getElementById('eye-open-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (!passwordInput || !toggleButton || !eyeOpenIcon || !eyeOffIcon) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                const showing = passwordInput.type === 'text';
                passwordInput.type = showing ? 'password' : 'text';
                toggleButton.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
                eyeOpenIcon.classList.toggle('hidden', !showing);
                eyeOffIcon.classList.toggle('hidden', showing);
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login Admin - Sistem Manajemen Pengajuan SBU</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased dark:bg-slate-900 dark:text-slate-100">
        <main class="flex min-h-screen items-center justify-center px-6 py-12">
            <section class="w-full max-w-md rounded-lg border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="mb-8">
                    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Admin SBU</p>
                    <h1 class="mt-2 text-2xl font-bold text-slate-950 dark:text-white">Masuk ke sistem</h1>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Gunakan akun admin untuk mengelola pengajuan SBU.<br><br>
                        <b><u>Akun Demo:</u></b><br>
                        Email: <code>admin@sbu.local</code><br>
                        Password: <code>sayaganteng</code>
                    </p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="mt-2 block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                        <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600 dark:border-slate-600">
                        Ingat saya
                    </label>

                    <button type="submit" class="w-full rounded-md bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        Login
                    </button>
                </form>
            </section>
        </main>
    </body>
</html>

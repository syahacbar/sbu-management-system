<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? Setting::get('app_name', 'Sistem Manajemen Pengajuan SBU') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="bg-slate-50 font-sans text-slate-900 antialiased dark:bg-slate-900 dark:text-slate-100">
        @php
            use App\Models\Setting;
            $navigation = [
                ['label' => 'Dashboard', 'route' => 'dashboard'],
                [
                    'label' => 'Master SBU',
                    'children' => [
                        ['label' => 'KBLI', 'route' => 'master.kbli.index'],
                        ['label' => 'Klasifikasi', 'route' => 'master.classifications.index'],
                        ['label' => 'Subklasifikasi', 'route' => 'master.subclassifications.index'],
                        ['label' => 'Skema', 'route' => 'master.schemes.index'],
                    ],
                ],
                [
                    'label' => 'Referensi',
                    'children' => [
                        ['label' => 'Kualifikasi', 'route' => 'master.qualifications.index'],
                        ['label' => 'LSBU', 'route' => 'master.lsbu.index'],
                        ['label' => 'Asosiasi', 'route' => 'master.associations.index'],
                        ['label' => 'Bidang Keilmuan', 'route' => 'master.science-fields.index'],
                        ['label' => 'Peralatan BG', 'route' => 'master.bg-equipment.index'],
                        ['label' => 'Peralatan BS', 'route' => 'master.bs-equipment.index'],
                        ['label' => 'Item Neraca', 'route' => 'master.balance-items.index'],
                        ['label' => 'Template Dokumen', 'route' => 'master.document-templates.index'],
                    ],
                ],
                ['label' => 'Perusahaan', 'route' => 'companies.index'],
                ['label' => 'Arsip Global', 'route' => 'archives.global'],
                ['label' => 'Pengaturan', 'route' => 'settings.index', 'active' => 'settings.*'],
            ];
        @endphp

        <style>
            @media (min-width: 1024px) {
                .sidebar-group[data-collapsed="true"] > .sidebar-group-children {
                    display: none;
                }
                .sidebar-group[data-collapsed="false"] > .sidebar-group-children {
                    display: block;
                }
                .sidebar-group[data-collapsed="true"] .sidebar-group-chevron {
                    transform: rotate(0deg);
                }
                .sidebar-group[data-collapsed="false"] .sidebar-group-chevron {
                    transform: rotate(90deg);
                }
            }
        </style>

        <div class="min-h-screen lg:flex">
            <aside class="border-b border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 lg:fixed lg:inset-y-0 lg:left-0 lg:flex lg:w-72 lg:flex-col lg:border-b-0 lg:border-r">
                <div class="flex h-20 shrink-0 items-center border-b border-slate-200 dark:border-slate-700 px-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">{{ Setting::get('app_company_name', 'Internal Admin') }}</p>
                        <h1 class="text-lg font-bold text-slate-950 dark:text-white">{{ Setting::get('app_name', 'Manajemen SBU') }}</h1>
                    </div>
                </div>

                <nav class="flex gap-2 overflow-x-auto px-4 py-4 lg:block lg:flex-1 lg:space-y-1 lg:overflow-y-auto">
                    @foreach ($navigation as $item)
                        @if (isset($item['children']))
                            @php
                                $hasActiveChild = collect($item['children'])->contains(fn($child) => request()->routeIs($child['route'] . '*'));
                            @endphp
                            <div class="sidebar-group min-w-max lg:min-w-0" data-collapsed="{{ $hasActiveChild ? 'false' : 'true' }}">
                                <button
                                    onclick="toggleGroup(this)"
                                    class="flex w-full items-center justify-between rounded-md px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-400 transition hover:bg-slate-100 dark:text-slate-500 dark:hover:bg-slate-700"
                                >
                                    <span>{{ $item['label'] }}</span>
                                    <svg class="sidebar-group-chevron hidden h-4 w-4 transition-transform lg:block" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div class="sidebar-group-children flex gap-2 lg:block lg:space-y-1">
                                    @foreach ($item['children'] as $child)
                                        <a
                                            href="{{ route($child['route']) }}"
                                            class="block whitespace-nowrap rounded-md px-4 py-2.5 text-sm font-medium transition {{ request()->routeIs($child['route'] . '*') ? 'bg-emerald-700 text-white shadow-sm dark:bg-emerald-600' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white' }}"
                                        >
                                            {{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @php
                                $isActive = isset($item['active'])
                                    ? request()->routeIs($item['active'])
                                    : request()->routeIs($item['route']);
                            @endphp
                            <a
                                href="{{ route($item['route']) }}"
                                class="block whitespace-nowrap rounded-md px-4 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-emerald-700 text-white shadow-sm dark:bg-emerald-600' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white' }}"
                            >
                                {{ $item['label'] }}
                            </a>
                        @endif
                    @endforeach
                </nav>
            </aside>

            <div class="flex min-h-screen flex-1 flex-col lg:pl-72">
                <header class="sticky top-0 z-10 border-b border-slate-200 bg-white/95 px-6 py-4 backdrop-blur dark:border-slate-700 dark:bg-slate-800/95">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ Setting::get('app_name', 'Sistem Manajemen Pengajuan SBU') }}</p>
                            <h2 class="text-xl font-semibold text-slate-950 dark:text-white">{{ $title ?? 'Dashboard' }}</h2>
                        </div>

                        <div class="flex items-center gap-2">
                            <button onclick="toggleTheme()" class="rounded-md border border-slate-300 p-2 text-slate-600 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-400 dark:hover:bg-slate-700" title="Ganti tema">
                                <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                                <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                                </svg>
                            </button>

                            <a href="{{ route('panduan') }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700" title="Buku Panduan Aplikasi">
                                Panduan App
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-6 py-6">
                    @isset($company)
                        @php
                            $workspaceNavigation = [
                                ['label' => 'Ringkasan', 'route' => 'companies.workspace.dashboard', 'active' => 'companies.workspace.dashboard'],
                                ['label' => 'Profil', 'route' => 'companies.workspace.profile.edit', 'active' => 'companies.workspace.profile.*'],
                                ['label' => 'Direktur/PJBU', 'route' => 'companies.workspace.directors_pjbus', 'active' => ['companies.workspace.directors_pjbus', 'companies.workspace.directors.*', 'companies.workspace.pjbus.*']],
                                ['label' => 'Pengajuan', 'route' => 'companies.workspace.applications.index', 'active' => 'companies.workspace.applications.*'],
                                ['label' => 'Tenaga Ahli', 'route' => 'companies.workspace.experts.index', 'active' => 'companies.workspace.experts.*'],
                                ['label' => 'Peralatan', 'route' => 'companies.workspace.equipment.index', 'active' => 'companies.workspace.equipment.*'],
                                ['label' => 'Neraca', 'route' => 'companies.workspace.balance.index', 'active' => 'companies.workspace.balance.*'],
                                ['label' => 'Dokumen', 'route' => 'companies.workspace.documents.index', 'active' => 'companies.workspace.documents.*'],
                                ['label' => 'Generate', 'route' => 'companies.workspace.generate.index', 'active' => 'companies.workspace.generate.*'],
                                ['label' => 'Arsip', 'route' => 'companies.workspace.archives.index', 'active' => 'companies.workspace.archives.*'],
                            ];
                        @endphp
 
                        <section class="mb-6 rounded-lg border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
                            <div class="mb-3 flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Workspace Perusahaan</p>
                                    <h3 class="text-lg font-semibold text-slate-950 dark:text-white">{{ $company->name }}</h3>
                                </div>
                                <a href="{{ route('companies.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-950 dark:text-slate-400 dark:hover:text-white">
                                    Kembali ke daftar perusahaan
                                </a>
                            </div>

                            <nav class="flex gap-2 overflow-x-auto">
                                @foreach ($workspaceNavigation as $item)
                                    @php
                                        $isActive = is_array($item['active'])
                                            ? request()->routeIs(...$item['active'])
                                            : request()->routeIs($item['active']);
                                    @endphp
                                    <a
                                        href="{{ route($item['route'], $company) }}"
                                        class="block whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium transition {{ $isActive ? 'bg-slate-900 text-white dark:bg-slate-600' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white' }}"
                                    >
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </nav>
                        </section>
                    @endisset

                    {{ $slot }}
                </main>
            </div>
        </div>
        <script>
            function toggleGroup(button) {
                const group = button.closest('.sidebar-group');
                const collapsed = group.dataset.collapsed === 'true';
                group.dataset.collapsed = collapsed ? 'false' : 'true';
            }

            function toggleTheme() {
                const html = document.documentElement;
                if (html.classList.contains('dark')) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        </script>
    </body>
</html>

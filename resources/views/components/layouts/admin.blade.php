<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Sistem Manajemen Pengajuan SBU' }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 font-sans text-slate-900 antialiased">
        @php
            $navigation = [
                ['label' => 'Dashboard', 'route' => 'dashboard'],
                [
                    'label' => 'Master',
                    'children' => [
                        ['label' => 'KBLI', 'route' => 'master.kbli.index'],
                        ['label' => 'Klasifikasi', 'route' => 'master.classifications.index'],
                        ['label' => 'Subklasifikasi', 'route' => 'master.subclassifications.index'],
                        ['label' => 'Skema', 'route' => 'master.schemes.index'],
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
                ['label' => 'Pengaturan', 'route' => 'settings.index'],
            ];
        @endphp

        <div class="min-h-screen lg:flex">
            <aside class="border-b border-slate-200 bg-white lg:fixed lg:inset-y-0 lg:left-0 lg:w-72 lg:border-b-0 lg:border-r">
                <div class="flex h-20 items-center border-b border-slate-200 px-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Internal Admin</p>
                        <h1 class="text-lg font-bold text-slate-950">Manajemen SBU</h1>
                    </div>
                </div>

                <nav class="flex gap-2 overflow-x-auto px-4 py-4 lg:block lg:space-y-1 lg:overflow-visible">
                    @foreach ($navigation as $item)
                        @if (isset($item['children']))
                            <div class="min-w-max lg:min-w-0">
                                <p class="px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-400">
                                    {{ $item['label'] }}
                                </p>

                                <div class="flex gap-2 lg:block lg:space-y-1">
                                    @foreach ($item['children'] as $child)
                                        <a
                                            href="{{ route($child['route']) }}"
                                            class="block whitespace-nowrap rounded-md px-4 py-2.5 text-sm font-medium transition {{ request()->routeIs($child['route']) ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950' }}"
                                        >
                                            {{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a
                                href="{{ route($item['route']) }}"
                                class="block whitespace-nowrap rounded-md px-4 py-2.5 text-sm font-medium transition {{ request()->routeIs($item['route']) ? 'bg-emerald-700 text-white shadow-sm' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950' }}"
                            >
                                {{ $item['label'] }}
                            </a>
                        @endif
                    @endforeach
                </nav>
            </aside>

            <div class="flex min-h-screen flex-1 flex-col lg:pl-72">
                <header class="sticky top-0 z-10 border-b border-slate-200 bg-white/95 px-6 py-4 backdrop-blur">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-500">Sistem Manajemen Pengajuan SBU</p>
                            <h2 class="text-xl font-semibold text-slate-950">{{ $title ?? 'Dashboard' }}</h2>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                                Logout
                            </button>
                        </form>
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
 
                        <section class="mb-6 rounded-lg border border-slate-200 bg-white p-4">
                            <div class="mb-3 flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Workspace Perusahaan</p>
                                    <h3 class="text-lg font-semibold text-slate-950">{{ $company->name }}</h3>
                                </div>
                                <a href="{{ route('companies.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-950">
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
                                        class="block whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium transition {{ $isActive ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-950' }}"
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
    </body>
</html>

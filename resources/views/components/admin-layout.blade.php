@props(['title' => 'Panel Admin', 'header' => null])

@php
    $pengguna = auth()->user();
    $menuAdmin = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.index',
            'active' => request()->routeIs('admin.index'),
        ],
        [
            'label' => 'Kelola Berita',
            'route' => 'admin.berita.index',
            'active' => request()->routeIs('admin.berita.index') || request()->routeIs('admin.berita.edit'),
        ],
        [
            'label' => 'Tambah Berita',
            'route' => 'admin.berita.create',
            'active' => request()->routeIs('admin.berita.create'),
        ],
        [
            'label' => 'Kelola Anggota',
            'route' => 'admin.anggota.index',
            'active' => request()->routeIs('admin.anggota.index'),
        ],
        [
            'label' => 'Tambah Anggota',
            'route' => 'admin.anggota.create',
            'active' => request()->routeIs('admin.anggota.create'),
        ],
        [
            'label' => 'Struktur Organisasi',
            'route' => 'admin.pengurus.index',
            'active' => request()->routeIs('admin.pengurus.*'),
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — MWC NU Ajibarang</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,500;6..72,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Proyek ini memakai Tailwind melalui CDN pada seluruh halaman Blade. --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#174A35',
                            dark: '#0E3022',
                            deeper: '#08251A',
                            surface: '#F6F7F3',
                            accent: '#B69A5A',
                            border: '#E4E7DF',
                            muted: '#68706B'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['Newsreader', 'serif']
                    }
                }
            }
        };
    </script>

    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        ::selection { background: #174A35; color: #fff; }
        .nav-scroll::-webkit-scrollbar { height: 6px; }
        .nav-scroll::-webkit-scrollbar-thumb { background: #cbd5c3; border-radius: 999px; }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-brand-surface font-sans text-slate-800 antialiased">
<div x-data="{ mobileOpen: false, profileOpen: false }" class="flex min-h-screen flex-col">

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50 border-b border-brand-border bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto max-w-[1500px] px-4 sm:px-6 lg:px-8">
            <div class="flex h-[68px] items-center justify-between gap-4">

                {{-- Logo --}}
                <a href="{{ route('admin.index') }}" class="flex shrink-0 items-center gap-3">
                    <img src="{{ asset('images/logo-nu.jpg') }}" alt="Logo NU"
                         class="h-10 w-10 rounded-full border border-brand-border object-contain">
                    <span class="leading-tight">
                        <span class="block text-sm font-extrabold tracking-tight text-brand">MWC NU Ajibarang</span>
                        <span class="block text-[10px] font-semibold uppercase tracking-[0.16em] text-brand-muted">Panel Administrasi</span>
                    </span>
                </a>

                {{-- Menu desktop --}}
                <nav class="hidden items-center gap-1 lg:flex" aria-label="Navigasi admin">
                    @foreach ($menuAdmin as $menu)
                        <a href="{{ route($menu['route']) }}"
                           @class([
                               'rounded-lg px-3 py-2 text-[12.5px] font-bold transition',
                               'bg-brand text-white shadow-sm' => $menu['active'],
                               'text-slate-600 hover:bg-emerald-50 hover:text-brand' => ! $menu['active'],
                           ])>
                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                </nav>

                {{-- Profil desktop --}}
                <div class="hidden items-center gap-2 lg:flex">
                    <a href="{{ route('home') }}" target="_blank" rel="noopener"
                       class="rounded-lg border border-brand-border px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-brand hover:text-brand">
                        Lihat Situs ↗
                    </a>

                    <div class="relative" @click.away="profileOpen = false">
                        <button type="button" @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2 rounded-xl border border-brand-border bg-white px-3 py-1.5 text-left transition hover:border-brand/40 hover:bg-emerald-50/50"
                                :aria-expanded="profileOpen.toString()" aria-haspopup="true">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand text-xs font-extrabold uppercase text-white">
                                {{ mb_substr($pengguna?->name ?? 'A', 0, 1) }}
                            </span>
                            <span class="hidden xl:block leading-tight">
                                <span class="block max-w-[140px] truncate text-xs font-bold text-slate-800">{{ $pengguna?->name ?? 'Administrator' }}</span>
                                <span class="block max-w-[140px] truncate text-[10px] text-brand-muted">{{ $pengguna?->email }}</span>
                            </span>
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        <div x-show="profileOpen" x-cloak
                             class="absolute right-0 mt-2 w-60 overflow-hidden rounded-2xl border border-brand-border bg-white p-2 shadow-xl">
                            <div class="border-b border-slate-100 px-3 py-2.5">
                                <p class="truncate text-sm font-bold text-slate-800">{{ $pengguna?->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $pengguna?->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}"
                               class="mt-1 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-brand">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/>
                                </svg>
                                Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-semibold text-red-600 hover:bg-red-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12H3m0 0 4-4m-4 4 4 4m6-9h4a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-4"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Hamburger --}}
                <button type="button" @click="mobileOpen = ! mobileOpen"
                        class="inline-flex items-center justify-center rounded-lg border border-brand-border p-2 text-brand transition hover:bg-emerald-50 lg:hidden"
                        :aria-expanded="mobileOpen.toString()" aria-controls="menu-admin-mobile" aria-label="Buka menu admin">
                    <svg x-show="! mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Menu mobile --}}
        <div id="menu-admin-mobile" x-show="mobileOpen" x-cloak
             class="border-t border-brand-border bg-white lg:hidden">
            <div class="mx-auto max-w-[1500px] px-4 py-4 sm:px-6">
                <nav class="grid gap-1" aria-label="Navigasi admin mobile">
                    @foreach ($menuAdmin as $menu)
                        <a href="{{ route($menu['route']) }}" @click="mobileOpen = false"
                           @class([
                               'flex items-center justify-between rounded-xl px-3.5 py-3 text-sm font-bold transition',
                               'bg-brand text-white' => $menu['active'],
                               'text-slate-600 hover:bg-emerald-50 hover:text-brand' => ! $menu['active'],
                           ])>
                            <span>{{ $menu['label'] }}</span>
                            @if ($menu['active'])
                                <span class="h-1.5 w-1.5 rounded-full bg-brand-accent"></span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <div class="mt-4 flex items-center gap-3 rounded-2xl border border-brand-border bg-brand-surface p-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand text-sm font-extrabold uppercase text-white">
                        {{ mb_substr($pengguna?->name ?? 'A', 0, 1) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-slate-800">{{ $pengguna?->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ $pengguna?->email }}</p>
                    </div>
                </div>

                <div class="mt-2 grid grid-cols-2 gap-2">
                    <a href="{{ route('profile.edit') }}"
                       class="rounded-xl border border-brand-border px-4 py-2.5 text-center text-xs font-bold text-slate-600 hover:border-brand hover:text-brand">
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-xs font-bold text-red-700 hover:bg-red-100">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Notifikasi --}}
    <div class="mx-auto w-full max-w-[1500px] px-4 pt-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 shadow-sm"
                 role="status">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/>
                </svg>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm" role="alert">
                <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.9 2.4 18a2 2 0 0 0 1.7 3h15.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                </svg>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        @if (session('info'))
            <div class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-semibold text-sky-900 shadow-sm" role="status">
                {{ session('info') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 shadow-sm" role="alert">
                <p class="font-bold">Ada data yang perlu diperbaiki:</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Judul halaman --}}
    @if ($header)
        <div class="border-b border-brand-border/70 bg-white/60">
            <div class="mx-auto max-w-[1500px] px-4 py-6 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </div>
    @endif

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="mt-10 border-t border-brand-border bg-white/70">
        <div class="mx-auto flex max-w-[1500px] flex-col gap-2 px-4 py-6 text-xs text-brand-muted sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p class="font-semibold text-brand">© {{ date('Y') }} MWC NU Kecamatan Ajibarang</p>
            <p>Panel administrasi · Data ditampilkan langsung dari basis data.</p>
        </div>
    </footer>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struktur Organisasi | MWC NU Ajibarang</title>

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,500;6..72,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#174A35',
                            dark: '#0E3022',
                            surface: '#FAF9F5',
                            accent: '#B69A5A',
                            border: '#EAE6DC',
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
</head>

<body class="min-h-screen bg-brand-surface font-sans text-neutral-900 antialiased selection:bg-[#174A35] selection:text-white overflow-x-hidden">

@php
    $tingkatan = [
        'mustasyar' => ['judul' => 'Dewan Mustasyar', 'keterangan' => 'Dewan Pertimbangan'],
        'syuriyah' => ['judul' => 'Pengurus Syuriyah', 'keterangan' => 'Pimpinan Keagamaan'],
        'tanfidziyah' => ['judul' => 'Pengurus Tanfidziyah', 'keterangan' => 'Pelaksana Organisasi'],
        'banom' => ['judul' => 'Badan Otonom (Banom)', 'keterangan' => 'Badan Otonom Nahdlatul Ulama'],
    ];
    $pengurusPerTingkatan = collect($tingkatan)->mapWithKeys(function ($data, $key) use ($pengurus) {
        return [$key => $pengurus->filter(function ($item) use ($key) {
            $level = strtolower(trim((string) ($item->tingkatan ?? '')));
            if ($key === 'banom') {
                return in_array($level, ['banom','gp ansor','muslimat nu','fatayat nu','ipnu','ippnu','pagar nusa','isnu','pergunu','ippnu','ipnu'], true)
                    || str_contains($level, 'ansor') || str_contains($level, 'muslimat') || str_contains($level, 'fatayat')
                    || str_contains($level, 'pagar nusa') || str_contains($level, 'pergunu') || str_contains($level, 'isnu');
            }
            return $level === $key;
        })];
    });
@endphp

    <!-- BACKDROP MOBILE -->
    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/60 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"></div>

    <!-- HEADER MOBILE -->
    <header class="sticky top-0 z-30 flex items-center justify-between border-b border-brand-border bg-white px-4 py-3 md:hidden">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <img src="{{ asset('images/logo-nu.jpg') }}" alt="Logo NU" class="h-9 w-9 rounded-full object-contain border border-neutral-200">
            <div>
                <p class="text-sm font-bold text-[#174A35] leading-tight">MWC NU Ajibarang</p>
                <p class="text-[10px] text-brand-muted">Kecamatan Ajibarang</p>
            </div>
        </a>

        <button id="toggle-sidebar" type="button" aria-label="Buka Menu" class="rounded-lg p-2 text-neutral-700 hover:bg-neutral-100 transition-colors focus:outline-none">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </header>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-[#174A35] text-white transition-transform duration-300 ease-in-out md:translate-x-0 shadow-xl md:shadow-none">

        <div class="flex items-center justify-between border-b border-white/10 px-5 py-5">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="relative overflow-hidden rounded-full p-0.5 bg-white/20">
                    <img src="{{ asset('images/logo-nu.jpg') }}" alt="Logo NU" class="h-10 w-10 rounded-full bg-white object-contain group-hover:scale-105 transition-transform">
                </div>
                <div>
                    <p class="text-sm font-bold tracking-tight text-white group-hover:text-[#D9C28D] transition-colors">MWC NU Ajibarang</p>
                    <p class="text-[11px] text-white/70">Banyumas, Jawa Tengah</p>
                </div>
            </a>

            <button id="close-sidebar" type="button" aria-label="Tutup Menu" class="rounded-lg p-1 text-white/70 hover:bg-white/10 hover:text-white md:hidden">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- MENU NAVIGASI -->
        <nav class="flex-1 space-y-1.5 px-3 py-6 text-sm font-medium">

            <a href="{{ route('home') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200 {{ request()->routeIs('home') ? 'bg-white/15 text-white font-semibold shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Beranda</span>
            </a>

            <a href="{{ route('berita.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200 {{ request()->routeIs('berita.*') ? 'bg-white/15 text-white font-semibold shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span>Berita</span>
            </a>

            <a href="{{ route('pengurus.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200 {{ request()->routeIs('pengurus.*') ? 'bg-white/15 text-white font-semibold shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857a5.002 5.002 0 00-9.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Struktur Organisasi</span>
            </a>
            
            @if (\Illuminate\Support\Facades\Route::has('kartu.index'))
                <a href="{{ route('kartu.index') }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200 {{ request()->routeIs('kartu.*') ? 'bg-white/15 text-white font-semibold shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <svg class="h-5 w-5 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6"/>
                    </svg>
                    <span>Kartu Anggota</span>
                </a>
            @endif

            <!-- PANEL ADMIN -->
            @auth
                @if (auth()->user()->is_admin)
                    <a href="{{ route('admin.index') }}"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-white transition hover:bg-white/10">
                        <span>⚙️</span>
                        <span>Panel Admin</span>
                    </a>
                @endif
            @endauth
        </nav>

        <!-- FOOTER SIDEBAR -->
        <div class="border-t border-white/10 p-4">
            @auth
                <div class="mb-3 flex items-center gap-3 rounded-lg bg-white/5 p-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#B69A5A] text-xs font-bold text-white uppercase">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="truncate text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-[10px] text-white/60">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-white/20 px-4 py-2 text-xs font-medium text-white/90 hover:bg-white/10 transition-colors">
                        Keluar
                    </button>
                </form>
            @else
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="flex w-full items-center justify-center gap-2 rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-[#174A35] hover:bg-[#F8F7F2] transition-colors shadow-sm">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="flex w-full items-center justify-center gap-2 rounded-lg border border-white/30 px-4 py-2.5 text-xs font-semibold text-white hover:bg-white/10 transition-colors">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="md:pl-64 flex flex-col min-h-screen">
        <main class="flex-grow">

            <!-- HEADER & FILTER -->
            <section class="border-b border-brand-border bg-white py-10 md:py-14">
                <div class="mx-auto max-w-6xl px-5 sm:px-6">

                    <nav class="mb-5 flex items-center gap-2 text-xs text-brand-muted">
                        <a href="{{ route('home') }}" class="hover:text-brand transition-colors">Beranda</a>
                        <span>/</span>
                        <span class="font-semibold text-brand">Struktur Organisasi</span>
                    </nav>

                    <div class="max-w-3xl">
                        <span class="mb-3 inline-block rounded-md bg-brand/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-brand">
                            Profil Organisasi
                        </span>

                        <h1 class="font-serif text-3xl font-medium leading-tight tracking-tight sm:text-4xl md:text-5xl">
                            Struktur Organisasi
                        </h1>

                        <p class="mt-4 text-base leading-relaxed text-brand-muted sm:text-lg font-light">
                            Mengenal jajaran pengurus Majelis Wakil Cabang Nahdlatul Ulama Kecamatan Ajibarang.
                        </p>

                        <div class="mt-6 h-1 w-20 rounded-full bg-brand-accent"></div>
                    </div>

                    <!-- FILTER TINGKATAN -->
                    <div id="section-nav" class="mt-8 flex gap-2 overflow-x-auto border-t border-brand-border/60 pt-6 pb-1">
                        <button type="button" data-level="all" class="level-btn shrink-0 rounded-full bg-brand px-5 py-2 text-xs font-semibold text-white sm:text-sm transition-all shadow-xs">
                            Semua Tingkatan
                        </button>

                        @foreach ($tingkatan as $key => $data)
                            <button type="button" data-level="{{ $key }}" class="level-btn shrink-0 rounded-full border border-neutral-200 bg-white px-5 py-2 text-xs font-medium text-neutral-600 hover:border-brand sm:text-sm transition-all">
                                {{ $key === 'banom' ? 'Banom' : $data['judul'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- DAFTAR PENGURUS -->
            <div class="mx-auto flex max-w-6xl flex-col gap-14 px-5 py-12 sm:px-6">

                @foreach ($tingkatan as $key => $data)
                    @php
                        $daftar = $pengurusPerTingkatan[$key];
                    @endphp

                    <section id="level-{{ $key }}" class="org-section scroll-mt-24">
                        <div class="mb-7 flex flex-col justify-between gap-3 border-b border-brand-border pb-4 sm:flex-row sm:items-end">
                            <div>
                                <span class="mb-1 block text-xs font-semibold uppercase tracking-widest text-brand-accent">
                                    {{ $data['keterangan'] }}
                                </span>

                                <h2 class="font-serif text-2xl font-medium text-brand sm:text-3xl">
                                    {{ $data['judul'] }}
                                </h2>
                            </div>

                            <span class="self-start rounded-full border border-brand-border bg-white px-3 py-1 text-xs text-brand-muted sm:self-auto">
                                {{ $daftar->count() }} data pengurus
                            </span>
                        </div>

                        @if ($daftar->isNotEmpty())
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($daftar as $item)
                                    <article class="group flex flex-col justify-between rounded-2xl border border-brand-border bg-white p-6 transition duration-300 hover:-translate-y-1 hover:border-brand/30 hover:shadow-lg">
                                        <div class="flex items-start gap-4">
                                            @if ($item->foto)
                                                <img
                                                    src="{{ asset('storage/' . $item->foto) }}"
                                                    alt="Foto {{ $item->nama }}"
                                                    class="h-12 w-12 shrink-0 rounded-xl object-cover"
                                                    loading="lazy"
                                                >
                                            @else
                                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-brand">
                                                    <span class="material-symbols-outlined">person</span>
                                                </div>
                                            @endif

                                            <div class="min-w-0 flex-1">
                                                <span class="mb-2 inline-block rounded-full bg-brand/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-brand">
                                                    {{ $item->jabatan ?? 'Pengurus' }}
                                                </span>

                                                <h3 class="break-words font-serif text-lg font-semibold text-neutral-900 transition group-hover:text-brand">
                                                    {{ $item->nama ?? 'Nama belum tersedia' }}
                                                </h3>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-brand-border bg-white px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-3xl text-brand-accent">groups</span>

                                <h3 class="mt-3 font-semibold text-neutral-800">Data belum tersedia</h3>

                                <p class="mt-2 text-sm text-brand-muted">
                                    Data untuk tingkatan ini akan ditampilkan setelah tersedia di database.
                                </p>
                            </div>
                        @endif
                    </section>
                @endforeach

                <!-- BANNER CTA -->
                <section class="rounded-3xl bg-brand p-8 text-white sm:p-10 shadow-xl">
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand-accent">Informasi Organisasi</span>

                    <h2 class="mt-3 font-serif text-2xl sm:text-3xl">
                        Mengenal kepengurusan NU Ajibarang
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80 font-light">
                        Informasi susunan pengurus ditampilkan berdasarkan data resmi yang tersedia pada sistem.
                        Untuk informasi lebih lanjut, silakan hubungi sekretariat MWC NU Ajibarang.
                    </p>

                    <a href="{{ route('home') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-brand transition hover:bg-brand-surface shadow-md">
                        Kembali ke Beranda <span aria-hidden="true">→</span>
                    </a>
                </section>
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="border-t border-brand-border bg-white py-8">
            <div class="mx-auto flex max-w-6xl flex-col gap-2 px-5 text-xs text-brand-muted sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p class="font-semibold text-brand">MWC NU Kecamatan Ajibarang</p>
                <p>© {{ date('Y') }} MWC NU Kecamatan Ajibarang. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- SCRIPT SIDEBAR & FILTER -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleSidebarBtn = document.getElementById('toggle-sidebar');
        const closeSidebarBtn = document.getElementById('close-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar?.classList.remove('-translate-x-full');
            sidebarOverlay?.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeSidebar() {
            sidebar?.classList.add('-translate-x-full');
            sidebarOverlay?.classList.add('opacity-0', 'pointer-events-none');
        }

        toggleSidebarBtn?.addEventListener('click', openSidebar);
        closeSidebarBtn?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);

        const levelButtons = document.querySelectorAll('.level-btn');
        const sections = document.querySelectorAll('.org-section');

        levelButtons.forEach(button => {
            button.addEventListener('click', () => {
                const level = button.dataset.level;

                levelButtons.forEach(item => {
                    item.classList.remove('bg-brand', 'text-white', 'shadow-xs');
                    item.classList.add('bg-white', 'text-neutral-600', 'border', 'border-neutral-200');
                });

                button.classList.add('bg-brand', 'text-white', 'shadow-xs');
                button.classList.remove('bg-white', 'text-neutral-600', 'border', 'border-neutral-200');

                sections.forEach(section => {
                    section.style.display =
                        level === 'all' || section.id === 'level-' + level
                            ? 'block'
                            : 'none';
                });

                if (level !== 'all') {
                    document.getElementById('level-' + level)
                        ?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    </script>

</body>
</html>
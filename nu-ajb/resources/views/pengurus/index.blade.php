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
        'mustasyar' => [
            'judul' => 'Dewan Mustasyar',
            'keterangan' => 'Dewan Pertimbangan',
        ],
        'syuriyah' => [
            'judul' => 'Pengurus Syuriyah',
            'keterangan' => 'Pimpinan Keagamaan',
        ],
        'tanfidziyah' => [
            'judul' => 'Pengurus Tanfidziyah',
            'keterangan' => 'Pelaksana Organisasi',
        ],
        'banom' => [
            'judul' => 'Badan Otonom (Banom)',
            'keterangan' => 'Badan Otonom Nahdlatul Ulama',
        ],
    ];

    // DATA SIMULASI UNTUK DEMO — hapus setelah data resmi tersedia.
    $pengurus = collect($pengurus)->concat([
        (object) ['nama' => 'Ahmad Fikri Maulana', 'jabatan' => 'Ketua GP Ansor', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Muhammad Rizal Fauzan', 'jabatan' => 'Sekretaris GP Ansor', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Siti Nur Azizah', 'jabatan' => 'Ketua Muslimat NU', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Nur Hidayah', 'jabatan' => 'Sekretaris Muslimat NU', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Aisyah Rahmawati', 'jabatan' => 'Ketua Fatayat NU', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Laila Fitriyani', 'jabatan' => 'Sekretaris Fatayat NU', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Fajar Ramadhan', 'jabatan' => 'Ketua IPNU', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Dimas Nur Huda', 'jabatan' => 'Sekretaris IPNU', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Putri Amalia Safitri', 'jabatan' => 'Ketua IPPNU', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Nabila Khairunnisa', 'jabatan' => 'Sekretaris IPPNU', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Arif Setiawan', 'jabatan' => 'Ketua Pagar Nusa', 'tingkatan' => 'banom', 'foto' => null],
        (object) ['nama' => 'Ahmad Zainuddin', 'jabatan' => 'Sekretaris Pagar Nusa', 'tingkatan' => 'banom', 'foto' => null],
    ]);

    $pengurusPerTingkatan = collect($tingkatan)->mapWithKeys(
        fn ($data, $key) => [
            $key => $pengurus->filter(
                fn ($item) => strtolower(trim($item->tingkatan ?? '')) === $key
            )
        ]
    );
@endphp

    <!-- NAVBAR RESPONSIF -->
    <header class="sticky top-0 z-40 border-b border-brand-border bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
                <img src="{{ asset('images/logo-nu.jpg') }}" alt="Logo NU" class="h-10 w-10 rounded-full border border-neutral-200 object-contain">
                <span>
                    <span class="block text-sm font-bold leading-tight text-brand sm:text-base">MWC NU Ajibarang</span>
                    <span class="block text-[11px] text-brand-muted">Banyumas, Jawa Tengah</span>
                </span>
            </a>

            <button id="mobile-menu-button" type="button" aria-label="Buka navigasi" aria-expanded="false"
                class="rounded-lg p-2 text-brand hover:bg-brand/10 md:hidden">
                <span class="material-symbols-outlined">menu</span>
            </button>

            <nav id="main-navigation" class="absolute left-0 right-0 top-full hidden border-b border-brand-border bg-white p-4 shadow-lg md:static md:flex md:items-center md:gap-1 md:border-0 md:bg-transparent md:p-0 md:shadow-none">
                <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('home') ? 'bg-brand/10 text-brand' : 'text-neutral-700 hover:bg-neutral-100' }}">Beranda</a>
                <a href="{{ route('berita.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('berita.*') ? 'bg-brand/10 text-brand' : 'text-neutral-700 hover:bg-neutral-100' }}">Berita</a>
                <a href="{{ route('pengurus.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('pengurus.*') ? 'bg-brand/10 text-brand' : 'text-neutral-700 hover:bg-neutral-100' }}">Struktur Organisasi</a>
                <a href="{{ route('home') }}#tentang-kami" class="block rounded-lg px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100">Tentang Kami</a>

                @if (\Illuminate\Support\Facades\Route::has('kartu.index'))
                    <a href="{{ route('kartu.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('kartu.*') ? 'bg-brand/10 text-brand' : 'text-neutral-700 hover:bg-neutral-100' }}">Kartu Anggota</a>
                @endif
                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-100">Panel Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="md:ml-2">
                        @csrf
                        <button type="submit" class="w-full rounded-lg border border-brand/20 px-3 py-2 text-left text-sm font-semibold text-brand hover:bg-brand/5 md:text-center">Keluar ({{ auth()->user()->name }})</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block rounded-lg px-3 py-2 text-sm font-semibold text-brand hover:bg-brand/5">Login</a>
                    <a href="{{ route('register') }}" class="block rounded-lg bg-brand px-3 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <div class="flex min-h-screen flex-col">
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
            <div class="mx-auto flex max-w-7xl flex-col gap-16 px-5 py-14 sm:px-6 lg:px-8">

                @foreach ($tingkatan as $key => $data)
                    @php
                        $daftar = $pengurusPerTingkatan[$key];
                    @endphp

                    <section id="level-{{ $key }}" class="org-section scroll-mt-24">
                        <div class="mb-8 flex flex-col justify-between gap-4 border-b-2 border-brand-border pb-5 sm:flex-row sm:items-end">
                            <div>
                                <span class="mb-1 block text-xs font-semibold uppercase tracking-widest text-brand-accent">
                                    {{ $data['keterangan'] }}
                                </span>

                                <h2 class="font-serif text-3xl font-medium text-brand sm:text-4xl">
                                    {{ $data['judul'] }}
                                </h2>
                            </div>

                            <span class="self-start rounded-full border border-brand-border bg-white px-3 py-1 text-xs text-brand-muted sm:self-auto">
                                {{ $daftar->count() }} data pengurus
                            </span>
                        </div>

                        @if ($daftar->isNotEmpty())
                            <div class="grid grid-cols-1 items-stretch gap-7 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($daftar as $item)
                                    <article class="group overflow-hidden rounded-2xl border border-brand-border bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand/40 hover:shadow-xl">
                                        <!-- FOTO PROFIL BESAR -->
                                        <div class="relative overflow-hidden bg-[#F0F1EC]">
                                            @if ($item->foto)
                                                <img
                                                    src="{{ asset('storage/' . $item->foto) }}"
                                                    alt="Foto {{ $item->nama }}"
                                                    class="aspect-[4/3] w-full object-cover object-center transition duration-500 group-hover:scale-105"
                                                    loading="lazy"
                                                >
                                            @else
                                                <div class="flex aspect-[4/3] w-full flex-col items-center justify-center gap-2 bg-gradient-to-br from-[#E7EEE8] to-[#F7F6F0] text-brand">
                                                    <span class="material-symbols-outlined text-6xl">person</span>
                                                    <span class="text-xs font-medium text-brand-muted">Foto belum tersedia</span>
                                                </div>
                                            @endif
                                            <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/35 to-transparent"></div>
                                        </div>

                                        <!-- INFORMASI PENGURUS -->
                                        <div class="flex min-h-36 flex-col p-5 sm:p-6">
                                            <span class="mb-3 inline-flex w-fit max-w-full items-center rounded-full bg-brand/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-brand">
                                                {{ $item->jabatan ?? 'Pengurus' }}
                                            </span>

                                            <h3 class="break-words font-serif text-xl font-semibold leading-snug text-neutral-900 transition group-hover:text-brand sm:text-2xl">
                                                {{ $item->nama ?? 'Nama belum tersedia' }}
                                            </h3>

                                            <div class="mt-auto pt-4">
                                                <div class="h-1 w-12 rounded-full bg-brand-accent transition-all duration-300 group-hover:w-20"></div>
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

    <!-- SCRIPT NAVBAR & FILTER -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mainNavigation = document.getElementById('main-navigation');

        mobileMenuButton?.addEventListener('click', () => {
            const isHidden = mainNavigation.classList.toggle('hidden');
            mobileMenuButton.setAttribute('aria-expanded', String(!isHidden));
        });

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
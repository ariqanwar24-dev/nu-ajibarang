<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struktur Organisasi | MWC NU Ajibarang</title>

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

<body class="min-h-screen overflow-x-hidden bg-brand-surface font-sans text-neutral-900 antialiased selection:bg-[#174A35] selection:text-white">

    <x-public-navbar />

    <div class="flex min-h-screen flex-col">
        <main class="flex-grow">
            <section class="border-b border-brand-border bg-white py-10 md:py-14">
                <div class="mx-auto max-w-7xl px-5 sm:px-6 lg:px-8">
                    <nav class="mb-5 flex flex-wrap items-center gap-2 text-xs text-brand-muted" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}" class="transition-colors hover:text-brand">Beranda</a>
                        <span>/</span>
                        <span class="font-semibold text-brand">Struktur Organisasi</span>
                    </nav>

                    <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_300px] lg:items-end">
                        <div class="max-w-3xl">
                            <span class="mb-3 inline-block rounded-md bg-brand/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-brand">
                                Profil Organisasi
                            </span>

                            <h1 class="font-serif text-3xl font-medium leading-tight tracking-tight sm:text-4xl md:text-5xl">
                                Struktur Organisasi
                            </h1>

                            <p class="mt-4 text-base font-light leading-relaxed text-brand-muted sm:text-lg">
                                Mengenal jajaran pengurus Majelis Wakil Cabang Nahdlatul Ulama Kecamatan Ajibarang berdasarkan data yang tersimpan di basis data organisasi.
                            </p>

                            <div class="mt-6 h-1 w-20 rounded-full bg-brand-accent"></div>

                            @if ($jumlahDemo > 0)
                                <div class="mt-6 flex max-w-3xl items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3.5 text-amber-950">
                                    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                                        <span class="material-symbols-outlined text-[18px]">info</span>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <aside class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-2">
                            <div class="rounded-2xl border border-brand-border bg-brand-surface p-4">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-muted">Total data</p>
                                <p class="mt-2 font-serif text-3xl font-semibold text-brand">{{ $pengurus->count() }}</p>
                                <p class="mt-1 text-[11px] text-brand-muted">pengurus tercatat</p>
                            </div>
                            <div class="rounded-2xl border border-brand-border bg-brand p-4 text-white">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-white/60">Kategori</p>
                                <p class="mt-2 font-serif text-3xl font-semibold">{{ count($kategori) }}</p>
                                <p class="mt-1 text-[11px] text-white/70">kelompok organisasi</p>
                            </div>
                            <div class="col-span-2 rounded-2xl border border-brand-border bg-white p-4 sm:col-span-1 lg:col-span-2">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-muted">Sumber data</p>
                                <p class="mt-2 text-xs font-semibold leading-relaxed text-slate-700">
                                    Dikelola melalui dashboard admin dan ditampilkan langsung dari tabel
                                    <span class="font-mono text-[10px] text-brand">penguruses</span>.
                                </p>
                            </div>
                        </aside>
                    </div>

                    <div id="section-nav" class="mt-8 flex gap-2 overflow-x-auto border-t border-brand-border/60 pb-1 pt-6">
                        <button type="button" data-level="all" aria-pressed="true"
                                class="level-btn shrink-0 rounded-full bg-brand px-5 py-2 text-xs font-semibold text-white shadow-sm transition-all sm:text-sm">
                            Semua Kategori
                        </button>

                        @foreach ($kategori as $key => $data)
                            <button type="button" data-level="{{ $key }}" aria-pressed="false"
                                    class="level-btn shrink-0 rounded-full border border-neutral-200 bg-white px-5 py-2 text-xs font-medium text-neutral-600 transition-all hover:border-brand sm:text-sm">
                                {{ $data['filter'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </section>

            <div class="mx-auto flex max-w-7xl flex-col gap-16 px-5 py-12 sm:px-6 lg:px-8">
                @foreach ($kategori as $key => $data)
                    @php
                        $daftar = $pengurusPerKategori->get($key, collect());
                    @endphp

                    <section id="level-{{ $key }}" class="org-section scroll-mt-28">
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
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($daftar as $item)
                                    <article class="group flex flex-col overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand/30 hover:shadow-xl">
                                        <a href="{{ route('pengurus.show', $item) }}"
                                           class="relative block h-72 overflow-hidden bg-brand-surface sm:h-80 lg:h-96"
                                           aria-label="Lihat profil {{ $item->nama }}">
                                            @if ($item->foto_url)
                                                <img src="{{ $item->foto_url }}"
                                                     alt="Foto {{ $item->nama }}"
                                                     class="h-full w-full object-cover object-top transition duration-500 group-hover:scale-[1.03]"
                                                     loading="lazy"
                                                     decoding="async">
                                            @else
                                                <div class="flex h-full min-h-72 flex-col items-center justify-center bg-gradient-to-br from-brand via-brand-dark to-[#0A2A1D] px-6 text-center">
                                                    <span class="flex h-24 w-24 items-center justify-center rounded-full border border-white/15 bg-white/10 font-serif text-5xl font-medium text-white">
                                                        {{ mb_strtoupper(mb_substr($item->nama, 0, 1)) }}
                                                    </span>
                                                    <span class="mt-5 text-[10px] font-bold uppercase tracking-[0.24em] text-white/55">
                                                        Foto belum tersedia
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/45 to-transparent"></div>

                                            @if ($item->is_demo)
                                                <span class="absolute left-4 top-4 rounded-full border border-white/20 bg-black/45 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[0.16em] text-white backdrop-blur">
                                                    Data contoh
                                                </span>
                                            @endif

                                            @if ($item->banomName())
                                                <span class="absolute bottom-4 left-4 rounded-full bg-white/95 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wide text-brand shadow-sm">
                                                    {{ $item->banomName() }}
                                                </span>
                                            @endif
                                        </a>

                                        <div class="flex flex-1 flex-col p-5 sm:p-6">
                                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-accent">
                                                {{ $item->jabatan ?: 'Pengurus' }}
                                            </p>

                                            <h3 class="mt-2 font-serif text-xl font-semibold leading-snug text-neutral-900 transition group-hover:text-brand sm:text-2xl">
                                                {{ $item->nama }}
                                            </h3>

                                            <p class="mt-2 text-xs font-medium text-brand-muted">
                                                {{ $item->kategoriLabel() }}
                                            </p>

                                            <a href="{{ route('pengurus.show', $item) }}"
                                               class="mt-5 inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-wide text-brand transition hover:text-brand-dark">
                                                Lihat profil
                                                <span aria-hidden="true">→</span>
                                            </a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-brand-border bg-white px-6 py-12 text-center">
                                <span class="material-symbols-outlined text-3xl text-brand-accent">groups</span>

                                <h3 class="mt-3 font-semibold text-neutral-800">Data belum tersedia</h3>

                                <p class="mt-2 text-sm text-brand-muted">
                                    Data untuk kategori ini akan ditampilkan setelah ditambahkan melalui dashboard admin.
                                </p>
                            </div>
                        @endif
                    </section>
                @endforeach

                <section class="rounded-3xl bg-brand p-8 text-white shadow-xl sm:p-10">
                    <span class="text-xs font-semibold uppercase tracking-widest text-brand-accent">Informasi Organisasi</span>

                    <h2 class="mt-3 font-serif text-2xl sm:text-3xl">
                        Mengenal kepengurusan NU Ajibarang
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm font-light leading-7 text-white/80">
                        Halaman ini menampilkan data yang dikelola melalui dashboard admin. Untuk memperoleh
                        susunan pengurus resmi atau informasi lebih lanjut, silakan hubungi sekretariat MWC NU Ajibarang.
                    </p>

                    <a href="{{ route('home') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-brand shadow-md transition hover:bg-brand-surface">
                        Kembali ke Beranda <span aria-hidden="true">→</span>
                    </a>
                </section>
            </div>
        </main>

        <footer class="border-t border-brand-border bg-white py-8">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 text-xs text-brand-muted sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
                <p class="font-semibold text-brand">MWC NU Kecamatan Ajibarang</p>
                <p>© {{ date('Y') }} MWC NU Kecamatan Ajibarang. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        const levelButtons = document.querySelectorAll('.level-btn');
        const sections = document.querySelectorAll('.org-section');

        levelButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const level = button.dataset.level;

                levelButtons.forEach((item) => {
                    item.setAttribute('aria-pressed', 'false');
                    item.classList.remove('bg-brand', 'text-white', 'shadow-sm');
                    item.classList.add('bg-white', 'text-neutral-600', 'border', 'border-neutral-200');
                });

                button.setAttribute('aria-pressed', 'true');
                button.classList.add('bg-brand', 'text-white', 'shadow-sm');
                button.classList.remove('bg-white', 'text-neutral-600', 'border', 'border-neutral-200');

                sections.forEach((section) => {
                    section.style.display = level === 'all' || section.id === `level-${level}`
                        ? 'block'
                        : 'none';
                });

                if (level !== 'all') {
                    document.getElementById(`level-${level}`)?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                    });
                } else {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    </script>

</body>
</html>

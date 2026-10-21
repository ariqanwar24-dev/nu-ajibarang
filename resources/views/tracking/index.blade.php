<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Lacak Berita & Kegiatan | MWC NU Ajibarang</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms,line-clamp"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#174A35',
                            dark: '#0E3022',
                            surface: '#F8F7F2',
                            accent: '#B69A5A',
                            border: '#E5E0D5',
                            muted: '#636A66'
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

<body class="flex min-h-screen flex-col bg-[#FBFBF9] font-sans text-neutral-900 antialiased selection:bg-brand selection:text-white">
    <x-public-navbar />

    <main class="flex-grow">
        {{-- Banner pencarian --}}
        <section class="relative overflow-hidden border-b border-brand-border bg-brand-surface py-10 sm:py-14">
            <div class="pointer-events-none absolute -left-16 -top-16 h-72 w-72 rounded-full bg-brand/10 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-20 right-0 h-72 w-72 rounded-full bg-brand-accent/15 blur-3xl" aria-hidden="true"></div>

            <div class="relative z-10 mx-auto max-w-6xl px-5 sm:px-6">
                <nav class="mb-6 flex flex-wrap items-center gap-2 text-xs font-semibold text-brand-muted" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="transition hover:text-brand">Beranda</a>
                    <span aria-hidden="true">/</span>
                    <span class="text-brand" aria-current="page">Lacak Berita</span>
                </nav>

                <p class="inline-flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.24em] text-brand-accent">
                    <span class="h-px w-7 bg-brand-accent" aria-hidden="true"></span>
                    Pencarian Publikasi
                </p>
                <h1 class="mt-3 max-w-3xl font-serif text-3xl font-medium leading-tight tracking-tight text-brand sm:text-4xl lg:text-5xl">
                    Lacak Berita &amp; Kegiatan NU
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-brand-muted sm:text-base">
                    Cari berita, warta kegiatan, dan pengumuman MWC NU Ajibarang berdasarkan judul atau kata kunci.
                    Buka detail berita untuk melihat status publikasi dan lokasi kegiatan bila tersedia.
                </p>
            </div>
        </section>

        <div class="mx-auto max-w-6xl px-5 py-8 sm:px-6 sm:py-10">
            {{-- Formulir pencarian --}}
            <section class="-mt-14 rounded-3xl border border-brand-border bg-white p-5 shadow-lg shadow-neutral-900/5 sm:p-7" aria-labelledby="judul-pencarian">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 id="judul-pencarian" class="text-base font-extrabold tracking-tight text-neutral-900">
                            Cari berita
                        </h2>
                        <p class="mt-1 text-xs leading-6 text-brand-muted">
                            Masukkan kata pada judul, isi berita, kategori, atau nama kegiatan.
                        </p>
                    </div>

                    @if ($bolehLihatStatusInternal)
                        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide text-amber-800 ring-1 ring-amber-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500" aria-hidden="true"></span>
                            Mode admin · status internal
                        </span>
                    @endif
                </div>

                <form action="{{ route('tracking.index') }}" method="GET" role="search" class="mt-5">
                    <label for="q" class="mb-2 block text-xs font-extrabold uppercase tracking-wider text-neutral-600">
                        Judul atau kata kunci berita
                    </label>

                    <div class="flex flex-col gap-2 sm:flex-row">
                        <div class="relative min-w-0 flex-1">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-neutral-400" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0Z"/>
                                </svg>
                            </span>
                            <input
                                id="q"
                                name="q"
                                type="search"
                                value="{{ old('q', $kataKunci) }}"
                                minlength="3"
                                maxlength="100"
                                required
                                autocomplete="off"
                                spellcheck="false"
                                aria-describedby="bantuan-pencarian @error('q') error-pencarian @enderror"
                                placeholder="Contoh: pengajian, santri, Harlah NU"
                                class="w-full rounded-xl border-brand-border bg-white py-3 pl-11 pr-4 text-sm text-neutral-800 shadow-sm transition placeholder:text-neutral-400 focus:border-brand focus:ring-brand"
                            >
                        </div>

                        <button type="submit"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-brand px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0Z"/>
                            </svg>
                            Cari Berita
                        </button>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                        <p id="bantuan-pencarian" class="text-[11px] leading-5 text-brand-muted">
                            Minimal 3 karakter. Gunakan kata yang spesifik agar hasil lebih tepat.
                        </p>

                        @if ($sudahDicari)
                            <a href="{{ route('tracking.index') }}" class="text-[11px] font-bold text-brand transition hover:text-brand-dark">
                                Bersihkan pencarian
                            </a>
                        @endif
                    </div>

                    @error('q')
                        <p id="error-pencarian" class="mt-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700">
                            {{ $message }}
                        </p>
                    @enderror
                </form>
            </section>

            @if ($sudahDicari)
                @if ($berita->isNotEmpty())
                    <section class="mt-8" aria-labelledby="judul-hasil">
                        <div class="flex flex-col gap-2 border-b-2 border-brand pb-3 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <h2 id="judul-hasil" class="font-serif text-2xl font-medium text-neutral-900">
                                    Hasil pencarian
                                </h2>
                                <p class="mt-1 text-xs text-brand-muted">
                                    <span class="font-bold text-neutral-700">{{ $berita->total() }}</span> berita ditemukan untuk
                                    <span class="font-bold text-brand">&ldquo;{{ $kataKunci }}&rdquo;</span>
                                </p>
                            </div>
                            <p class="text-[11px] font-semibold text-brand-muted">
                                Halaman {{ $berita->currentPage() }} dari {{ $berita->lastPage() }}
                            </p>
                        </div>

                        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($berita as $item)
                                @php
                                    $gambar = $item->gambarUrl();
                                    $tanggal = $item->published_at ?? $item->created_at;
                                    $statusKelas = match ($item->status) {
                                        \App\Models\Berita::STATUS_PUBLISHED => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                        \App\Models\Berita::STATUS_PENDING => 'bg-amber-50 text-amber-800 ring-amber-200',
                                        \App\Models\Berita::STATUS_REJECTED => 'bg-red-50 text-red-700 ring-red-200',
                                        default => 'bg-slate-100 text-slate-600 ring-slate-200',
                                    };
                                    $lokasi = $item->lokasiRingkas();
                                    $mapsUrl = $item->lokasiMapsUrl();
                                @endphp

                                <article class="group flex flex-col overflow-hidden rounded-2xl border border-brand-border bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                                    <a href="{{ route('berita.show', $item) }}" class="block aspect-[16/9] overflow-hidden bg-neutral-100" aria-label="Baca {{ $item->judul }}">
                                        @if ($gambar)
                                            <img src="{{ $gambar }}"
                                                 alt="{{ $item->judul }}"
                                                 class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                                 loading="lazy"
                                                 decoding="async">
                                        @else
                                            <div class="flex h-full flex-col items-center justify-center gap-2 bg-brand-surface px-4 text-center">
                                                <svg class="h-9 w-9 text-brand/25" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2M7 16h6M7 8h6v4H7V8Z"/>
                                                </svg>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-muted">MWC NU Ajibarang</span>
                                            </div>
                                        @endif
                                    </a>

                                    <div class="flex flex-1 flex-col p-5">
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1.5 text-[10px] font-bold uppercase tracking-wider">
                                            <time class="text-brand-muted">{{ $tanggal?->format('d M Y') ?? 'Tanggal tidak tersedia' }}</time>

                                            @if (filled($item->kategori))
                                                <span class="text-neutral-300" aria-hidden="true">&bull;</span>
                                                <span class="text-brand">{{ $item->kategori }}</span>
                                            @endif

                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 ring-1 {{ $statusKelas }}">
                                                <span class="h-1 w-1 rounded-full bg-current" aria-hidden="true"></span>
                                                {{ $item->statusLabel() }}
                                            </span>
                                        </div>

                                        <h3 class="mt-2 line-clamp-2 font-serif text-lg font-semibold leading-snug text-neutral-900 transition group-hover:text-brand">
                                            <a href="{{ route('berita.show', $item) }}">{{ $item->judul }}</a>
                                        </h3>

                                        <p class="mt-2 line-clamp-3 text-xs leading-6 text-neutral-600 sm:text-sm">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($item->ringkasan ?: $item->isi), 150) }}
                                        </p>

                                        @if ($lokasi)
                                            <p class="mt-3 inline-flex items-start gap-1.5 text-[11px] font-semibold leading-5 text-brand-muted">
                                                <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-brand-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s6-5.1 6-10a6 6 0 1 0-12 0c0 4.9 6 10 6 10Zm0-8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                                </svg>
                                                <span class="line-clamp-2">{{ $lokasi }}</span>
                                            </p>
                                        @endif

                                        <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-5">
                                            <a href="{{ route('berita.show', $item) }}"
                                               class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-wider text-brand transition hover:gap-2.5">
                                                Lihat detail
                                                <span aria-hidden="true">&rarr;</span>
                                            </a>

                                            @if ($mapsUrl)
                                                <a href="{{ $mapsUrl }}"
                                                   target="_blank"
                                                   rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1.5 rounded-lg border border-brand-border px-2.5 py-1.5 text-[10.5px] font-bold text-brand transition hover:border-brand hover:bg-brand-surface"
                                                   aria-label="Lihat lokasi {{ $item->judul }} di Google Maps">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s6-5.1 6-10a6 6 0 1 0-12 0c0 4.9 6 10 6 10Zm0-8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                                    </svg>
                                                    Peta
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        @if ($berita->hasPages())
                            <nav class="mt-8 flex flex-wrap items-center justify-center gap-3" aria-label="Navigasi hasil pencarian">
                                @if ($berita->onFirstPage())
                                    <span class="rounded-xl border border-brand-border bg-white px-4 py-2 text-xs font-bold text-neutral-400">
                                        &larr; Sebelumnya
                                    </span>
                                @else
                                    <a href="{{ $berita->previousPageUrl() }}"
                                       class="rounded-xl border border-brand-border bg-white px-4 py-2 text-xs font-bold text-brand transition hover:border-brand hover:bg-brand-surface">
                                        &larr; Sebelumnya
                                    </a>
                                @endif

                                <span class="text-xs font-semibold text-brand-muted">
                                    Halaman {{ $berita->currentPage() }} dari {{ $berita->lastPage() }}
                                </span>

                                @if ($berita->hasMorePages())
                                    <a href="{{ $berita->nextPageUrl() }}"
                                       class="rounded-xl border border-brand-border bg-white px-4 py-2 text-xs font-bold text-brand transition hover:border-brand hover:bg-brand-surface">
                                        Berikutnya &rarr;
                                    </a>
                                @else
                                    <span class="rounded-xl border border-brand-border bg-white px-4 py-2 text-xs font-bold text-neutral-400">
                                        Berikutnya &rarr;
                                    </span>
                                @endif
                            </nav>
                        @endif
                    </section>
                @else
                    <section class="mt-8 rounded-3xl border border-brand-border bg-white p-8 text-center shadow-sm" role="status">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-surface text-brand-muted">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0Z"/>
                            </svg>
                        </div>
                        <h2 class="mt-4 font-serif text-2xl font-medium text-neutral-900">Berita tidak ditemukan</h2>
                        <p class="mx-auto mt-2 max-w-xl text-sm leading-7 text-brand-muted">
                            Tidak ada berita yang cocok dengan kata kunci
                            <span class="font-bold text-neutral-700">&ldquo;{{ $kataKunci }}&rdquo;</span>.
                            Periksa ejaan, gunakan kata yang lebih umum, atau jelajahi seluruh daftar berita.
                        </p>
                        <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('tracking.index') }}"
                               class="rounded-xl border border-brand-border bg-white px-4 py-2.5 text-xs font-bold text-brand transition hover:border-brand hover:bg-brand-surface">
                                Coba kata kunci lain
                            </a>
                            <a href="{{ route('berita.index') }}"
                               class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">
                                Lihat semua berita
                            </a>
                        </div>
                    </section>
                @endif
            @else
                <section class="mt-8 grid gap-4 sm:grid-cols-3" aria-label="Panduan pencarian berita">
                    <div class="rounded-2xl border border-brand-border bg-white p-5 shadow-sm">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand/10 text-brand" aria-hidden="true">
                            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0Z"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-sm font-extrabold text-neutral-900">Cari judul atau kata kunci</h2>
                        <p class="mt-1 text-xs leading-6 text-brand-muted">
                            Gunakan judul berita, nama kegiatan, kategori, atau kata yang muncul di dalam berita.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-brand-border bg-white p-5 shadow-sm">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand/10 text-brand" aria-hidden="true">
                            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M9 8h6M5 5h14v14H5z"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-sm font-extrabold text-neutral-900">Status publikasi</h2>
                        <p class="mt-1 text-xs leading-6 text-brand-muted">
                            Publik hanya melihat berita yang sudah terbit. Admin dapat melihat status draft, menunggu review, terbit, dan ditolak.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-brand-border bg-white p-5 shadow-sm">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand/10 text-brand" aria-hidden="true">
                            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s6-5.1 6-10a6 6 0 1 0-12 0c0 4.9 6 10 6 10Zm0-8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                            </svg>
                        </span>
                        <h2 class="mt-3 text-sm font-extrabold text-neutral-900">Lokasi kegiatan</h2>
                        <p class="mt-1 text-xs leading-6 text-brand-muted">
                            Jika alamat atau koordinat tersimpan, tombol Google Maps muncul di detail berita. Lokasi tidak pernah dikarang.
                        </p>
                    </div>
                </section>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-dashed border-brand-border bg-white/60 px-5 py-4 text-xs text-brand-muted">
                    <p>Ingin melihat seluruh publikasi tanpa mencari?</p>
                    <a href="{{ route('berita.index') }}" class="font-bold text-brand transition hover:text-brand-dark">
                        Buka halaman Berita &rarr;
                    </a>
                </div>
            @endif

            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-brand-border pt-5 text-xs text-brand-muted">
                <a href="{{ route('home') }}" class="font-bold text-brand transition hover:text-brand-dark">
                    &larr; Kembali ke Beranda
                </a>
                <p>MWC NU Kecamatan Ajibarang</p>
            </div>
        </div>
    </main>

    <footer class="border-t border-brand-border bg-white">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-5 py-5 text-[11px] text-brand-muted sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="font-semibold text-brand">MWC NU Kecamatan Ajibarang</p>
            <p>&copy; {{ date('Y') }} MWC NU Ajibarang. Seluruh hak cipta dilindungi.</p>
        </div>
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $berita->judul }} | MWC NU Ajibarang</title>

    <script src="https://cdn.tailwindcss.com?plugins=line-clamp"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Newsreader:opsz,wght@6..72,500;6..72,600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['Newsreader', 'serif']
                    }
                }
            }
        };
    </script>

    @if ($berita->memilikiKoordinat())
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    @endif

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .article-content { overflow-wrap: break-word; line-height: 1.9; }
        .article-content > p { margin: 0; }
        .article-content img { max-width: 100%; height: auto; border-radius: 0.75rem; margin: 1.5rem 0; }
        #peta-berita { min-height: 300px; z-index: 0; }
        .leaflet-control-attribution { font-size: 9px !important; }
    </style>
</head>

<body class="overflow-x-hidden bg-[#F8FAF8] font-sans text-neutral-800 antialiased selection:bg-[#174A35] selection:text-white">

    <!-- Navbar -->
    <x-public-navbar />

    <!-- Breadcrumb -->
    <nav class="mx-auto max-w-4xl px-5 pt-8" aria-label="Breadcrumb">
        <div class="flex flex-wrap items-center gap-2 text-sm text-neutral-500">
            <a href="{{ route('home') }}" class="transition hover:text-[#174A35]">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('berita.index') }}" class="transition hover:text-[#174A35]">Berita</a>
            <span aria-hidden="true">/</span>
            <span class="font-semibold text-[#174A35]" aria-current="page">Detail Berita</span>
        </div>
    </nav>

    <!-- Article -->
    <main class="mx-auto max-w-4xl px-5 pb-16 pt-8">
        @if (! $berita->isPublished())
            <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                <strong>Pratinjau {{ strtolower($berita->statusLabel()) }}.</strong>
                Berita ini belum terbit dan tidak tampil di halaman publik.
                @if ($berita->isRejected() && $berita->review_note)
                    <p class="mt-2 rounded-lg bg-white/70 px-3 py-2 text-xs">
                        <strong>Alasan penolakan:</strong> {{ $berita->review_note }}
                    </p>
                @endif
            </div>
        @endif

        <article class="overflow-hidden rounded-3xl border border-neutral-200 bg-white shadow-sm">
            @php
                $tanggalTampil = $berita->published_at ?? $berita->created_at;
                $penulisTampil = filled($berita->penulis) ? $berita->penulis : $berita->user?->name;
                $gambarUtama = $berita->gambarUrl();
                $lokasiMapsUrl = $berita->lokasiMapsUrl();
                $isiBerita = trim(strip_tags($berita->isi ?? ''));
                $paragrafBerita = $isiBerita === ''
                    ? []
                    : (preg_split('/\R{2,}/u', $isiBerita, -1, PREG_SPLIT_NO_EMPTY) ?: []);
            @endphp

            <!-- Article Header -->
            <header class="px-6 pb-7 pt-8 sm:px-10 sm:pt-10">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="inline-flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.24em] text-[#B69A5A]">
                        <span class="h-px w-7 bg-[#B69A5A]" aria-hidden="true"></span>
                        Berita NU Ajibarang
                    </p>

                    @if (! $berita->isPublished())
                        <span @class([
                            'rounded-full px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide',
                            'bg-amber-100 text-amber-800' => $berita->isPending(),
                            'bg-red-100 text-red-700' => $berita->isRejected(),
                            'bg-slate-200 text-slate-700' => $berita->isDraft(),
                        ])>
                            {{ $berita->statusLabel() }}
                        </span>
                    @endif
                </div>

                <h1 class="mt-4 max-w-3xl font-serif text-3xl font-semibold leading-[1.15] tracking-tight text-neutral-950 sm:text-4xl lg:text-[2.85rem]">
                    {{ $berita->judul }}
                </h1>

                <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2.5 border-t border-neutral-100 pt-4 text-xs font-semibold text-neutral-500 sm:text-sm">
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z"/>
                        </svg>
                        <time @if ($tanggalTampil) datetime="{{ $tanggalTampil->toDateString() }}" @endif>
                            {{ $tanggalTampil?->translatedFormat('d F Y') ?? 'Tanggal tidak tersedia' }}
                        </time>
                    </span>

                    @if (filled($berita->kategori))
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50/80 px-2.5 py-1 text-[#174A35]">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h10v10H7zM5 5h14v14H5z"/>
                            </svg>
                            {{ $berita->kategori }}
                        </span>
                    @endif

                    @if (filled($penulisTampil))
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/>
                            </svg>
                            {{ $penulisTampil }}
                        </span>
                    @endif
                </div>

                @if (filled($berita->ringkasan))
                    <p class="mt-6 max-w-3xl border-l-4 border-[#B69A5A] bg-amber-50/40 px-4 py-3 text-base font-medium leading-8 text-neutral-700 sm:text-lg sm:leading-9">
                        {{ $berita->ringkasan }}
                    </p>
                @endif
            </header>

            <!-- Featured Image -->
            <div class="border-y border-neutral-100 bg-neutral-50/60 px-6 py-5 sm:px-10 sm:py-6">
                @if ($gambarUtama)
                    <figure class="overflow-hidden rounded-2xl bg-neutral-100 shadow-sm">
                        <div class="aspect-[16/9] w-full overflow-hidden">
                            <img src="{{ $gambarUtama }}"
                                 alt="{{ $berita->judul }}"
                                 class="h-full w-full object-cover"
                                 loading="eager"
                                 decoding="async">
                        </div>
                    </figure>
                @else
                    <div class="flex min-h-[180px] flex-col items-center justify-center gap-2 rounded-2xl border border-dashed border-neutral-200 bg-white px-6 py-10 text-center sm:min-h-[240px]">
                        <svg class="h-9 w-9 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.6-4.6a2 2 0 0 1 2.8 0L16 16m-2-2 1.6-1.6a2 2 0 0 1 2.8 0L20 14M5 5h14a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm4 5h.01"/>
                        </svg>
                        <p class="text-xs font-bold uppercase tracking-wider text-neutral-500">Gambar utama belum tersedia</p>
                        <p class="max-w-sm text-xs leading-6 text-neutral-400">Berita tetap dapat dibaca melalui konten di bawah ini.</p>
                    </div>
                @endif
            </div>

            <!-- Article Content -->
            <div class="px-6 py-8 sm:px-10 sm:py-10">
                @if ($paragrafBerita !== [])
                    <div class="article-content space-y-5 text-[15.5px] leading-8 text-neutral-700 sm:text-base">
                        @foreach ($paragrafBerita as $paragraf)
                            <p class="whitespace-pre-line">{{ trim($paragraf) }}</p>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-neutral-200 bg-neutral-50 px-5 py-8 text-center">
                        <p class="text-sm font-semibold text-neutral-600">Isi berita belum tersedia.</p>
                    </div>
                @endif

                @if ($berita->galeri->isNotEmpty())
                    <section class="mt-10 border-t border-neutral-200 pt-7" aria-labelledby="judul-galeri-foto">
                        <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#B69A5A]">Dokumentasi</p>
                                <h2 id="judul-galeri-foto" class="mt-1 font-serif text-2xl font-medium text-neutral-900">Galeri Foto</h2>
                                <p class="mt-1 text-xs text-neutral-500">Klik foto untuk melihat ukuran lebih besar.</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wide text-[#174A35]">
                                {{ $berita->galeri->count() }} foto
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($berita->galeri as $foto)
                                @php
                                    $urlFoto = route('berita.galeri.show', [$berita, $foto]);
                                @endphp
                                <button type="button"
                                        class="group relative aspect-[4/3] overflow-hidden rounded-xl border border-neutral-200 bg-neutral-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#174A35] focus-visible:ring-offset-2"
                                        data-galeri-item
                                        data-src="{{ $urlFoto }}"
                                        data-alt="Foto galeri {{ $loop->iteration }}: {{ $berita->judul }}"
                                        aria-label="Perbesar foto galeri {{ $loop->iteration }} dari {{ $berita->galeri->count() }}">
                                    <img src="{{ $urlFoto }}"
                                         alt="Foto galeri {{ $loop->iteration }}: {{ $berita->judul }}"
                                         class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                         loading="lazy"
                                         decoding="async">
                                    <span class="absolute inset-0 flex items-center justify-center bg-black/0 text-white opacity-0 transition group-hover:bg-black/25 group-hover:opacity-100">
                                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-black/55 backdrop-blur">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M19 11a8 8 0 1 1-16 0 8 8 0 0 1 16 0ZM11 8v6m-3-3h6"/>
                                            </svg>
                                        </span>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- LOKASI ACARA --}}
                @if ($berita->memilikiLokasi())
                    <section class="mt-10 overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50/70">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-200 bg-white px-5 py-4">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#B69A5A]">Informasi Acara</p>
                                <h2 class="mt-0.5 font-serif text-xl font-medium text-neutral-900">Lokasi Acara</h2>
                            </div>
                            @if ($berita->memilikiKoordinat())
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wide text-emerald-800">
                                    Titik peta tersedia
                                </span>
                            @endif
                        </div>

                        <div class="grid gap-5 p-5 sm:p-6">
                            <div class="grid gap-4 sm:grid-cols-2">
                                @if ($berita->lokasi_nama)
                                    <div>
                                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-neutral-400">Nama Tempat</p>
                                        <p class="mt-1 text-sm font-bold text-neutral-800">{{ $berita->lokasi_nama }}</p>
                                    </div>
                                @endif

                                @if ($berita->lokasi_desa)
                                    <div>
                                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-neutral-400">Desa</p>
                                        <p class="mt-1 text-sm font-semibold text-neutral-800">{{ $berita->lokasi_desa }}</p>
                                    </div>
                                @endif

                                @if ($berita->lokasi_alamat)
                                    <div class="sm:col-span-2">
                                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-neutral-400">Alamat</p>
                                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-neutral-700">{{ $berita->lokasi_alamat }}</p>
                                    </div>
                                @endif

                                @if ($berita->acara_mulai)
                                    <div class="sm:col-span-2">
                                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-neutral-400">Waktu Acara</p>
                                        <p class="mt-1 text-sm font-semibold text-neutral-800">
                                            {{ $berita->acara_mulai->translatedFormat('l, d F Y · H:i') }}
                                            @if ($berita->acara_selesai)
                                                <span class="font-normal text-neutral-500">s.d. {{ $berita->acara_selesai->translatedFormat('d F Y · H:i') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                @if ($berita->lokasi_keterangan)
                                    <div class="sm:col-span-2">
                                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-neutral-400">Keterangan</p>
                                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-neutral-700">{{ $berita->lokasi_keterangan }}</p>
                                    </div>
                                @endif
                            </div>

                            @if ($berita->memilikiKoordinat())
                                @php
                                    $lat = number_format($berita->latitude, 7, '.', '');
                                    $lng = number_format($berita->longitude, 7, '.', '');
                                @endphp

                                <div id="peta-berita" class="w-full overflow-hidden rounded-xl border border-neutral-200 bg-neutral-100"></div>

                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ $lokasiMapsUrl }}" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-2 rounded-xl bg-[#174A35] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#103725]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11Z"/>
                                            <circle cx="12" cy="10" r="2.5" stroke-width="1.8"/>
                                        </svg>
                                        Lihat Lokasi di Google Maps
                                    </a>

                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $lat }},{{ $lng }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-2 rounded-xl border border-[#174A35] bg-white px-4 py-2.5 text-xs font-bold text-[#174A35] transition hover:bg-emerald-50">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 20 3-16m0 0 8 4-2.5 4L20 20l-8-4-3 4Z"/>
                                        </svg>
                                        Petunjuk Arah
                                    </a>

                                    <button type="button" id="tombol-rute-peta"
                                            class="inline-flex items-center gap-2 rounded-xl border border-neutral-300 bg-white px-4 py-2.5 text-xs font-bold text-neutral-700 transition hover:border-[#174A35] hover:text-[#174A35]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h6l3 6h7M4 18h5l4-6"/>
                                        </svg>
                                        Tampilkan Rute dari Lokasi Saya
                                    </button>
                                </div>

                                <div>
                                    <p id="status-rute" class="text-xs font-semibold text-neutral-500" role="status" aria-live="polite"></p>
                                    <p class="mt-1 text-[10.5px] leading-relaxed text-neutral-400">
                                        Garis rute dihitung menggunakan layanan OSRM publik dari lokasi perangkat Anda menuju titik acara.
                                        Ini bukan pelacakan GPS real-time; jarak dan waktu bersifat perkiraan dari layanan routing.
                                    </p>
                                </div>
                            @elseif ($lokasiMapsUrl)
                                <a href="{{ $lokasiMapsUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex w-fit items-center gap-2 rounded-xl bg-[#174A35] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#103725]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11Z"/>
                                        <circle cx="12" cy="10" r="2.5" stroke-width="1.8"/>
                                    </svg>
                                    Lihat Lokasi di Google Maps
                                </a>
                                <p class="rounded-xl border border-dashed border-neutral-200 bg-white px-4 py-3 text-xs text-neutral-500">
                                    Titik koordinat peta belum diisi untuk acara ini. Tautan Google Maps dibuat dari alamat yang tersimpan di atas.
                                </p>
                            @else
                                <p class="rounded-xl border border-dashed border-neutral-200 bg-white px-4 py-3 text-xs text-neutral-500">
                                    Lokasi belum tersedia. Alamat atau titik koordinat acara ini belum diisi, sehingga tombol Google Maps tidak ditampilkan.
                                </p>
                            @endif
                        </div>
                    </section>
                @endif

                <div class="mt-10 border-t border-neutral-200 pt-6">
                    <a href="{{ route('berita.index') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-[#174A35] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#103725]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7"/>
                        </svg>
                        Kembali ke Daftar Berita
                    </a>
                </div>
            </div>
        </article>

        @if ($beritaTerkait->isNotEmpty())
            <section class="mt-12" aria-labelledby="judul-berita-terkait">
                <div class="mb-5 flex flex-wrap items-end justify-between gap-3 border-b-2 border-[#174A35] pb-3">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.22em] text-[#B69A5A]">Baca juga</p>
                        <h2 id="judul-berita-terkait" class="mt-1 font-serif text-2xl font-medium text-neutral-900">Berita Terkait</h2>
                    </div>
                    <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#174A35] transition-all hover:gap-2.5">
                        Semua berita <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($beritaTerkait as $item)
                        @php
                            $gambarTerkait = $item->gambarUrl();
                            $tanggalTerkait = $item->published_at ?? $item->created_at;
                        @endphp
                        <article class="group overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <a href="{{ route('berita.show', $item) }}" class="flex h-full flex-col">
                                <div class="aspect-[16/9] overflow-hidden bg-neutral-100">
                                    @if ($gambarTerkait)
                                        <img src="{{ $gambarTerkait }}"
                                             alt="{{ $item->judul }}"
                                             class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                             loading="lazy"
                                             decoding="async">
                                    @else
                                        <div class="flex h-full items-center justify-center bg-[#F0F0EC] px-3 text-center">
                                            <span class="text-[10px] font-semibold uppercase tracking-wider text-neutral-400">Tanpa gambar</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-1 flex-col p-4">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[10px] font-bold uppercase tracking-wider">
                                        @if (filled($item->kategori))
                                            <span class="text-[#174A35]">{{ $item->kategori }}</span>
                                            <span class="text-neutral-300" aria-hidden="true">&bull;</span>
                                        @endif
                                        <time class="text-neutral-500">
                                            {{ $tanggalTerkait?->translatedFormat('d M Y') ?? 'Tanggal tidak tersedia' }}
                                        </time>
                                    </div>

                                    <h3 class="mt-2 line-clamp-2 font-serif text-lg font-semibold leading-snug text-neutral-900 transition group-hover:text-[#174A35]">
                                        {{ $item->judul }}
                                    </h3>

                                    <span class="mt-auto inline-flex items-center gap-1.5 pt-4 text-[11px] font-bold uppercase tracking-wider text-[#174A35]">
                                        Baca berita <span aria-hidden="true">&rarr;</span>
                                    </span>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <!-- Footer -->
    <footer class="border-t border-neutral-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 py-7 text-sm text-neutral-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
            <p class="font-semibold text-[#174A35]">MWC NU Kecamatan Ajibarang</p>
            <p>&copy; {{ date('Y') }} MWC NU Ajibarang. Seluruh hak cipta dilindungi.</p>
        </div>
    </footer>

    @if ($berita->galeri->isNotEmpty())
        <div id="galeri-lightbox"
             class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/90 p-4 sm:p-8"
             role="dialog"
             aria-modal="true"
             aria-label="Pratinjau galeri foto"
             aria-hidden="true">
            <button type="button" id="tutup-latar-galeri" class="absolute inset-0 h-full w-full cursor-zoom-out" aria-label="Tutup galeri"></button>

            <button type="button" id="tutup-galeri"
                    class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/10 text-2xl font-light text-white backdrop-blur transition hover:bg-white/20 sm:right-6 sm:top-6"
                    aria-label="Tutup galeri">
                ×
            </button>

            @if ($berita->galeri->count() > 1)
                <button type="button" id="galeri-sebelumnya"
                        class="absolute left-3 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-white/10 text-3xl font-light text-white backdrop-blur transition hover:bg-white/20 sm:left-6"
                        aria-label="Foto sebelumnya">
                    ‹
                </button>
                <button type="button" id="galeri-berikutnya"
                        class="absolute right-3 top-1/2 z-10 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-white/10 text-3xl font-light text-white backdrop-blur transition hover:bg-white/20 sm:right-6"
                        aria-label="Foto berikutnya">
                    ›
                </button>
            @endif

            <figure class="relative z-[1] flex max-h-full max-w-6xl flex-col items-center">
                <img id="galeri-lightbox-img" src="" alt=""
                     class="max-h-[78vh] max-w-full rounded-lg object-contain shadow-2xl">
                <figcaption id="galeri-lightbox-caption"
                            class="mt-3 rounded-full bg-black/45 px-4 py-1.5 text-xs font-semibold text-white/90 backdrop-blur">
                </figcaption>
            </figure>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const itemGaleri = Array.from(document.querySelectorAll('[data-galeri-item]'));
                const lightbox = document.getElementById('galeri-lightbox');
                const gambarLightbox = document.getElementById('galeri-lightbox-img');
                const captionLightbox = document.getElementById('galeri-lightbox-caption');
                const tombolTutup = document.getElementById('tutup-galeri');
                const latarTutup = document.getElementById('tutup-latar-galeri');
                const tombolSebelumnya = document.getElementById('galeri-sebelumnya');
                const tombolBerikutnya = document.getElementById('galeri-berikutnya');

                if (!lightbox || !gambarLightbox || itemGaleri.length === 0) {
                    return;
                }

                let indeksAktif = 0;

                const tampilkanFoto = (indeks) => {
                    indeksAktif = (indeks + itemGaleri.length) % itemGaleri.length;
                    const item = itemGaleri[indeksAktif];

                    gambarLightbox.src = item.dataset.src;
                    gambarLightbox.alt = item.dataset.alt || 'Foto galeri berita';
                    captionLightbox.textContent = (indeksAktif + 1) + ' / ' + itemGaleri.length + ' · ' + (item.dataset.alt || 'Foto galeri');
                };

                const bukaLightbox = (indeks) => {
                    tampilkanFoto(indeks);
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                    lightbox.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                    tombolTutup?.focus();
                };

                const tutupLightbox = () => {
                    lightbox.classList.add('hidden');
                    lightbox.classList.remove('flex');
                    lightbox.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                    gambarLightbox.src = '';
                };

                itemGaleri.forEach((item, indeks) => {
                    item.addEventListener('click', () => bukaLightbox(indeks));
                });

                tombolTutup?.addEventListener('click', tutupLightbox);
                latarTutup?.addEventListener('click', tutupLightbox);
                tombolSebelumnya?.addEventListener('click', () => tampilkanFoto(indeksAktif - 1));
                tombolBerikutnya?.addEventListener('click', () => tampilkanFoto(indeksAktif + 1));

                document.addEventListener('keydown', (event) => {
                    if (lightbox.classList.contains('hidden')) {
                        return;
                    }

                    if (event.key === 'Escape') {
                        tutupLightbox();
                    } else if (event.key === 'ArrowLeft') {
                        tampilkanFoto(indeksAktif - 1);
                    } else if (event.key === 'ArrowRight') {
                        tampilkanFoto(indeksAktif + 1);
                    }
                });
            });
        </script>
    @endif

    @if ($berita->memilikiKoordinat())
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tujuan = [{{ (float) $berita->latitude }}, {{ (float) $berita->longitude }}];
                const petaEl = document.getElementById('peta-berita');

                if (!petaEl || typeof L === 'undefined') {
                    return;
                }

                const peta = L.map(petaEl, { scrollWheelZoom: false }).setView(tujuan, 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
                }).addTo(peta);

                L.marker(tujuan).addTo(peta)
                    .bindPopup(@json($berita->lokasi_nama ?: $berita->judul))
                    .openPopup();

                const statusRute = document.getElementById('status-rute');
                const tombolRute = document.getElementById('tombol-rute-peta');
                let garisRute = null;
                let markerAsal = null;

                const aturStatus = (pesan, error = false) => {
                    if (!statusRute) return;
                    statusRute.textContent = pesan;
                    statusRute.className = 'text-xs font-semibold ' + (error ? 'text-red-600' : 'text-neutral-600');
                };

                tombolRute?.addEventListener('click', () => {
                    if (!navigator.geolocation) {
                        aturStatus('Perangkat ini tidak mendukung geolokasi. Gunakan tombol Petunjuk Arah untuk membuka Google Maps.', true);
                        return;
                    }

                    tombolRute.disabled = true;
                    aturStatus('Mengambil lokasi perangkat...');

                    navigator.geolocation.getCurrentPosition(
                        async (posisi) => {
                            aturStatus('Menghitung rute menuju lokasi acara...');

                            try {
                                const asal = [posisi.coords.latitude, posisi.coords.longitude];
                                const url = 'https://router.project-osrm.org/route/v1/driving/'
                                    + posisi.coords.longitude + ',' + posisi.coords.latitude + ';'
                                    + {{ (float) $berita->longitude }} + ',' + {{ (float) $berita->latitude }}
                                    + '?overview=full&geometries=geojson';

                                const response = await fetch(url);

                                if (!response.ok) {
                                    throw new Error('HTTP ' + response.status);
                                }

                                const data = await response.json();

                                if (!data.routes || data.routes.length === 0 || !data.routes[0].geometry) {
                                    throw new Error('Rute tidak ditemukan.');
                                }

                                const rute = data.routes[0];
                                const koordinat = rute.geometry.coordinates.map(([lng, lat]) => [lat, lng]);

                                if (garisRute) {
                                    peta.removeLayer(garisRute);
                                }

                                if (markerAsal) {
                                    peta.removeLayer(markerAsal);
                                }

                                markerAsal = L.marker(asal).addTo(peta).bindPopup('Titik awal perangkat');
                                garisRute = L.polyline(koordinat, {
                                    color: '#174A35',
                                    weight: 5,
                                    opacity: 0.85
                                }).addTo(peta);

                                peta.fitBounds(garisRute.getBounds(), { padding: [28, 28] });

                                const jarak = (rute.distance / 1000).toFixed(1);
                                const menit = Math.max(1, Math.round(rute.duration / 60));
                                aturStatus('Perkiraan rute: ' + jarak + ' km, sekitar ' + menit + ' menit perjalanan (OSRM).');
                            } catch (error) {
                                aturStatus('Rute gagal dihitung. Layanan routing sedang tidak tersedia; gunakan tombol Petunjuk Arah.', true);
                            } finally {
                                tombolRute.disabled = false;
                            }
                        },
                        () => {
                            aturStatus('Lokasi perangkat tidak dapat diambil. Izinkan akses lokasi atau gunakan tombol Petunjuk Arah.', true);
                            tombolRute.disabled = false;
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
                    );
                });
            });
        </script>
    @endif

</body>
</html>

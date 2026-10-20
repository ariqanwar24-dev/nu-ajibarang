<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $berita->judul }} | MWC NU Ajibarang</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Newsreader:opsz,wght@6..72,500;6..72,600&display=swap" rel="stylesheet">

    @if ($berita->memilikiKoordinat())
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    @endif

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .article-content { overflow-wrap: break-word; line-height: 1.9; }
        .article-content p { margin-bottom: 1.25rem; }
        .article-content img { max-width: 100%; height: auto; border-radius: 0.75rem; margin: 1.5rem 0; }
        #peta-berita { min-height: 300px; z-index: 0; }
        .leaflet-control-attribution { font-size: 9px !important; }
    </style>
</head>

<body class="bg-[#F8FAF8] text-neutral-800">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 border-b border-neutral-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-5 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo-nu.jpg') }}" alt="Logo NU" class="h-11 w-11 rounded-full object-contain">
                <div>
                    <p class="text-sm font-extrabold text-[#174A35]">MWC NU Ajibarang</p>
                    <p class="hidden text-xs text-neutral-500 sm:block">Majelis Wakil Cabang Nahdlatul Ulama</p>
                </div>
            </a>

            <a href="{{ route('berita.index') }}"
               class="rounded-lg border border-neutral-200 px-4 py-2 text-sm font-semibold transition hover:border-[#174A35] hover:bg-[#174A35] hover:text-white">
                Kembali ke Berita
            </a>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="mx-auto max-w-4xl px-5 pt-8">
        <div class="flex flex-wrap items-center gap-2 text-sm text-neutral-500">
            <a href="{{ route('home') }}" class="hover:text-[#174A35]">Beranda</a>
            <span>/</span>
            <a href="{{ route('berita.index') }}" class="hover:text-[#174A35]">Berita</a>
            <span>/</span>
            <span class="text-[#174A35]">Detail Berita</span>
        </div>
    </div>

    <!-- Article -->
    <main class="mx-auto max-w-4xl px-5 pb-16 pt-8">
        @if (! $berita->sudahTerbit())
            <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                <strong>Pratinjau draft.</strong> Berita ini belum terbit dan tidak tampil di halaman publik.
            </div>
        @endif

        <article class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">
            <!-- Article Header -->
            <header class="px-6 pb-6 pt-8 sm:px-10 sm:pt-10">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-bold text-[#174A35]">
                        <span class="h-2 w-2 rounded-full bg-[#174A35]"></span>
                        {{ $berita->kategori ?: 'BERITA NU AJIBARANG' }}
                    </span>

                    @if (! $berita->sudahTerbit())
                        <span class="rounded-full bg-slate-200 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide text-slate-700">Draft</span>
                    @endif
                </div>

                <h1 class="mt-5 text-2xl font-extrabold leading-tight tracking-tight text-neutral-900 sm:text-3xl lg:text-4xl">
                    {{ $berita->judul }}
                </h1>

                <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-neutral-500">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1Z"/>
                        </svg>
                        {{ $berita->created_at?->translatedFormat('d F Y') ?? 'Tanggal tidak tersedia' }}
                    </span>

                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/>
                        </svg>
                        {{ $berita->penulis ?: ($berita->user?->name ?? 'Redaksi MWC NU Ajibarang') }}
                    </span>

                    @if ($berita->acara_mulai)
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" stroke-width="1.8"/>
                                <path stroke-linecap="round" stroke-width="1.8" d="M12 7v5l3 2"/>
                            </svg>
                            {{ $berita->acara_mulai->translatedFormat('d M Y, H:i') }}
                        </span>
                    @endif
                </div>
            </header>

            <!-- Featured Image -->
            @if ($berita->gambar)
                <div class="px-6 sm:px-10">
                    <img src="{{ asset('storage/' . $berita->gambar) }}"
                         alt="{{ $berita->judul }}"
                         class="max-h-[500px] w-full rounded-xl object-cover">
                </div>
            @endif

            <!-- Article Content -->
            <div class="px-6 py-8 sm:px-10 sm:py-10">
                @if ($berita->ringkasan)
                    <p class="mb-6 border-l-4 border-[#B69A5A] bg-amber-50/50 px-4 py-3 text-base font-medium leading-8 text-neutral-700">
                        {{ $berita->ringkasan }}
                    </p>
                @endif

                <div class="article-content text-base text-neutral-700">
                    {!! nl2br(e(strip_tags($berita->isi))) !!}
                </div>

                {{-- LOKASI ACARA --}}
                @if ($berita->memilikiLokasi())
                    <section class="mt-10 overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50/70">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-neutral-200 bg-white px-5 py-4">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#B69A5A]">Informasi Acara</p>
                                <h2 class="mt-0.5 text-lg font-bold text-neutral-900">Lokasi Acara</h2>
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
                                    <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}" target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-2 rounded-xl bg-[#174A35] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#103725]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11Z"/>
                                            <circle cx="12" cy="10" r="2.5" stroke-width="1.8"/>
                                        </svg>
                                        Lihat Lokasi
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
                            @else
                                <p class="rounded-xl border border-dashed border-neutral-200 bg-white px-4 py-3 text-xs text-neutral-500">
                                    Titik koordinat peta belum diisi untuk acara ini. Alamat di atas tetap dapat digunakan sebagai petunjuk lokasi.
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
    </main>

    <!-- Footer -->
    <footer class="border-t border-neutral-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 py-7 text-sm text-neutral-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
            <p class="font-semibold text-[#174A35]">MWC NU Kecamatan Ajibarang</p>
            <p>&copy; {{ date('Y') }} MWC NU Ajibarang. Seluruh hak cipta dilindungi.</p>
        </div>
    </footer>

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

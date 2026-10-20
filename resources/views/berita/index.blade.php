<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Warta | MWC NU Ajibarang</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Tailwind CSS with Plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,line-clamp"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#174A35',
                            hover: '#113928',
                            surface: '#F8F7F2',
                            accent: '#B69A5A',
                            border: '#E5E0D5',
                            muted: '#636A66'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['Newsreader', 'serif']
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%, 100%': { opacity: '0.2' },
                            '50%': { opacity: '0.6' },
                        }
                    },
                    animation: {
                        'pulse-glow': 'pulseGlow 4s ease-in-out infinite',
                    }
                }
            }
        };
    </script>
</head>

<body class="bg-[#FBFBF9] text-neutral-900 font-sans antialiased selection:bg-[#174A35] selection:text-white flex flex-col min-h-screen overflow-x-hidden">

    <!-- NAVBAR -->
    <header class="sticky top-0 z-50 border-b border-brand-border bg-white/95 backdrop-blur-md">
        <nav class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo-nu.jpg') }}" alt="Logo NU"
                     class="h-11 w-11 rounded-full border border-neutral-200 object-contain">
                <div>
                    <p class="text-sm font-bold text-[#174A35]">MWC NU Ajibarang</p>
                    <p class="text-xs text-brand-muted">Banyumas, Jawa Tengah</p>
                </div>
            </a>

            <button id="toggle-navbar" type="button" aria-label="Buka menu"
                    aria-expanded="false"
                    class="rounded-lg p-2 text-[#174A35] hover:bg-gray-100 md:hidden">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div id="navbar-menu"
                 class="hidden w-full flex-col gap-2 pb-2 text-sm font-medium md:flex md:w-auto md:flex-row md:items-center md:gap-1 md:pb-0">
                <a href="{{ route('home') }}"
                   class="rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#174A35]">Beranda</a>
                <a href="{{ route('berita.index') }}"
                   class="rounded-lg bg-[#174A35] px-4 py-2 font-semibold text-white">Berita</a>
                <a href="{{ route('pengurus.index') }}"
                   class="rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#174A35]">Struktur Organisasi</a>
                <a href="{{ route('kartu.index') }}"
                   class="rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-[#174A35]">Kartu Anggota</a>
                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.index') }}"
                           class="rounded-lg px-4 py-2 text-gray-700 hover:bg-gray-100">Panel Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-lg px-4 py-2 text-left text-red-600 hover:bg-red-50 md:w-auto">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="rounded-lg bg-[#174A35] px-5 py-2 text-center font-semibold text-white hover:bg-[#113928]">Login</a>
                @endauth
            </div>
        </nav>
    </header>

    <div class="flex min-h-screen flex-col">

        <main class="flex-grow">

            <!-- HEADER BANNER DENGAN EFEK ELEGANT GLOW -->
            <section class="relative overflow-hidden bg-brand-surface border-b border-brand-border py-12 md:py-16">
                <!-- Ambient Glow Ornament -->
                <div class="absolute -top-12 -left-12 w-80 h-80 bg-[#174A35]/10 rounded-full blur-3xl pointer-events-none animate-pulse-glow"></div>
                <div class="absolute -bottom-10 right-0 w-72 h-72 bg-[#B69A5A]/15 rounded-full blur-3xl pointer-events-none animate-pulse-glow"></div>

                <div class="relative z-10 max-w-6xl mx-auto px-6">
                    <div class="max-w-2xl" data-aos="fade-right">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/80 border border-brand-border text-[11px] font-semibold uppercase tracking-[0.2em] text-[#174A35] shadow-xs mb-4">
                            <span class="w-2 h-2 rounded-full bg-[#174A35] animate-ping"></span>
                            Informasi Terkini
                        </span>
                        <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl text-[#174A35] font-medium tracking-tight mb-3">
                            Berita NU Ajibarang
                        </h1>
                        <p class="text-brand-muted text-base sm:text-lg font-light leading-relaxed">
                            Kabar, warta kegiatan, dan perkembangan organisasi Nahdlatul Ulama di Kecamatan Ajibarang.
                        </p>
                    </div>
                </div>
            </section>

            <!-- BERITA UTAMA GAYA PORTAL EDITORIAL -->
            @if ($berita->isNotEmpty())
                @php $beritaUtama = $berita->first(); @endphp
                <section class="max-w-6xl mx-auto px-6 pt-10" data-aos="fade-up">
                    <div class="mb-5 flex items-center gap-3 border-b-2 border-[#174A35] pb-3">
                        <span class="h-6 w-1.5 bg-[#174A35]"></span>
                        <h2 class="text-xl font-bold tracking-tight text-neutral-900">BERITA UTAMA</h2>
                    </div>

                    <div class="grid gap-7 lg:grid-cols-12">
                        <article class="group overflow-hidden border-b border-brand-border pb-6 lg:col-span-8">
                            <a href="{{ route('berita.show', $beritaUtama) }}" class="block">
                                <div class="aspect-[16/9] overflow-hidden bg-neutral-100">
                                    @if ($beritaUtama->gambar)
                                        <img src="{{ asset('storage/' . $beritaUtama->gambar) }}"
                                             alt="{{ $beritaUtama->judul }}"
                                             class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                                    @else
                                        <div class="flex h-full items-center justify-center bg-[#F0F0EC] text-sm text-gray-500">
                                            Foto berita tidak tersedia
                                        </div>
                                    @endif
                                </div>
                                <p class="mt-4 text-xs font-bold uppercase tracking-wider text-[#174A35]">MWC NU Ajibarang
                                    <span class="mx-1 text-gray-400">|</span>
                                    <time>{{ $beritaUtama->created_at?->format('d M Y') ?? 'Tanggal tidak tersedia' }}</time>
                                </p>
                                <h2 class="mt-2 font-serif text-3xl font-semibold leading-tight text-neutral-950 transition-colors group-hover:text-[#174A35] sm:text-4xl">
                                    {{ $beritaUtama->judul }}
                                </h2>
                                <p class="mt-3 max-w-3xl text-sm leading-7 text-neutral-600 sm:text-base">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($beritaUtama->ringkasan ?: $beritaUtama->isi), 220) }}
                                </p>
                                <span class="mt-4 inline-block text-sm font-bold text-[#174A35]">Baca selengkapnya →</span>
                            </a>
                        </article>

                        <aside class="lg:col-span-4">
                            <h3 class="mb-4 border-b border-brand-border pb-3 text-sm font-bold uppercase tracking-wider text-neutral-800">Berita lainnya</h3>
                            <div class="divide-y divide-brand-border">
                                @foreach ($berita->skip(1)->take(4) as $item)
                                    <a href="{{ route('berita.show', $item) }}" class="group flex gap-4 py-4 first:pt-0">
                                        <div class="h-24 w-28 shrink-0 overflow-hidden bg-neutral-100">
                                            @if ($item->gambar)
                                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}"
                                                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                            @else
                                                <div class="flex h-full items-center justify-center px-2 text-center text-[10px] text-gray-500">Tanpa gambar</div>
                                            @endif
                                        </div>
                                        <div>
                                            <time class="text-[11px] text-gray-500">{{ $item->created_at?->format('d M Y') ?? 'Tanggal tidak tersedia' }}</time>
                                            <h4 class="mt-1 line-clamp-3 text-sm font-bold leading-snug text-neutral-900 group-hover:text-[#174A35]">
                                                {{ $item->judul }}
                                            </h4>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </aside>
                    </div>
                </section>
            @endif

            <!-- DAFTAR BERITA TERBARU (GRID) -->
            <section id="daftar-berita" class="max-w-6xl mx-auto px-6 py-16">
                <div class="flex items-center justify-between mb-8 pb-3 border-b border-brand-border" data-aos="fade-right">
                    <div>
                        <h2 class="font-serif text-2xl md:text-3xl font-medium text-[#174A35]">
                            Berita Terbaru
                        </h2>
                        <p class="text-xs text-brand-muted mt-1">Daftar publikasi berita dan pengumuman resmi</p>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse ($berita->skip($berita->isNotEmpty() ? 1 : 0) as $item)
                        <article data-aos="fade-up" 
                                 data-aos-delay="{{ ($loop->index % 3) * 100 }}" 
                                 class="bg-white rounded-2xl border border-brand-border overflow-hidden flex flex-col group hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                            
                            <!-- Gambar Berita -->
                            <div class="relative h-48 sm:h-52 overflow-hidden bg-neutral-100">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}"
                                         alt="{{ $item->judul }}"
                                         class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500 ease-out">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center bg-brand-surface p-6 text-center">
                                        <svg class="w-10 h-10 text-[#174A35]/30 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                        <span class="text-[11px] font-medium text-brand-muted">MWC NU Ajibarang</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Konten Berita -->
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-accent"></span>
                                    <time class="text-[11px] font-semibold text-brand-muted tracking-wider">
                                        {{ $item->created_at?->format('d M Y') ?? 'Tanggal tidak tersedia' }}
                                    </time>
                                </div>

                                <h3 class="font-serif text-lg font-medium text-neutral-900 leading-snug group-hover:text-[#174A35] transition-colors mb-3 line-clamp-2">
                                    {{ $item->judul }}
                                </h3>

                                <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed mb-6 line-clamp-3 font-light">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->ringkasan ?: $item->isi), 100) }}
                                </p>

                                <a href="{{ route('berita.show', $item) }}"
                                   class="mt-auto inline-flex items-center gap-1.5 text-xs font-semibold text-[#174A35] group-hover:gap-2.5 transition-all duration-300">
                                    <span>Baca selengkapnya</span>
                                    <span class="transform group-hover:translate-x-1 transition-transform">→</span>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full py-16 text-center bg-white border border-dashed border-brand-border rounded-2xl" data-aos="fade-up">
                            <div class="w-14 h-14 bg-brand-surface rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-brand-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                            <h3 class="font-serif text-lg font-medium text-neutral-800 mb-1">Belum Ada Berita</h3>
                            <p class="text-xs sm:text-sm text-brand-muted">Belum ada berita yang diterbitkan untuk saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

        </main>

        <!-- FOOTER -->
        <footer class="border-t border-brand-border bg-white mt-12">
            <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-[#174A35]">MWC NU Ajibarang</span>
                    <span>•</span>
                    <span>Kecamatan Ajibarang, Kabupaten Banyumas</span>
                </div>
                <div>
                    © {{ date('Y') }} MWC NU Kecamatan Ajibarang. All rights reserved.
                </div>
            </div>
        </footer>

    </div>

    <!-- AOS & NAVBAR SCRIPT -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-out-cubic'
        });

        const toggleNavbar = document.getElementById('toggle-navbar');
        const navbarMenu = document.getElementById('navbar-menu');

        toggleNavbar?.addEventListener('click', () => {
            const isHidden = navbarMenu.classList.toggle('hidden');
            toggleNavbar.setAttribute('aria-expanded', String(!isHidden));
        });
    </script>

</body>
</html>
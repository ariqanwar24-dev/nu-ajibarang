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

    <!-- OVERLAY / BACKDROP MOBILE -->
    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/60 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"></div>

    <!-- HEADER KHUSUS MOBILE (DILENGKAPI TOMBOL HAMBURGER) -->
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

    <!-- SIDEBAR (DESKTOP FIXED & MOBILE DRAWER) -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-[#174A35] text-white transition-transform duration-300 ease-in-out md:translate-x-0 shadow-xl md:shadow-none">
        
        <!-- Logo & Header Sidebar -->
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

            <!-- Tombol Close (Mobile) -->
            <button id="close-sidebar" class="rounded-lg p-1 text-white/70 hover:bg-white/10 hover:text-white md:hidden">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigasi Menu Sidebar -->
        <nav class="flex-1 space-y-1.5 px-3 py-6 text-sm font-medium">
            <!-- Beranda -->
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200 {{ request()->routeIs('home') ? 'bg-white/15 text-white font-semibold shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Beranda</span>
            </a>

            <!-- Berita (AKTIF) -->
            <a href="{{ route('berita.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200 {{ request()->routeIs('berita.*') ? 'bg-white/15 text-white font-semibold shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span>Berita</span>
            </a>

            <!-- Struktur Organisasi -->
            <a href="{{ route('pengurus.index') }}"
               class="flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200 {{ request()->routeIs('pengurus.*') ? 'bg-white/15 text-white font-semibold shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Struktur Organisasi</span>
            </a>

            <!-- Tentang Kami -->
            <a href="{{ route('home') }}#tentang-kami"
               class="flex items-center gap-3 rounded-lg px-4 py-3 text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200">
                <svg class="h-5 w-5 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Tentang Kami</span>
            </a>

            <!-- Kartu Anggota -->
            <a href="{{ route('kartu.index') ?? '#' }}"
               class="flex items-center gap-3 rounded-lg px-4 py-3 transition-all duration-200 {{ request()->routeIs('kartu.*') ? 'bg-white/15 text-white font-semibold shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                <svg class="h-5 w-5 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6"/>
                </svg>
                <span>Kartu Anggota</span>
            </a>

             <!-- Panel Admin (Khusus Admin) -->
     @auth
    @if (auth()->user()->is_admin)
        <a href="{{ route('admin.index') }}"
           class="flex items-center gap-3 rounded-xl px-4 py-3
                  text-white transition hover:bg-white/10">
            <span>⚙️</span>
            <span>Panel Admin</span>
        </a>
    @endif
@endauth
        </nav>

        <!-- Footer Sidebar (Akun / Auth) -->
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
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
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

    <!-- WRAPPER KONTEN UTAMA (DILENGKAPI md:pl-64 AGAR TIDAK TERTUTUP SIDEBAR) -->
    <div class="md:pl-64 flex flex-col min-h-screen">

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

            <!-- BERITA UTAMA HIGHLIGHT CARD -->
            <section class="max-w-6xl mx-auto px-6 -mt-6 relative z-20" data-aos="fade-up">
                <div class="bg-white rounded-2xl border border-brand-border overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 grid md:grid-cols-12 group">
                    <div class="md:col-span-6 relative min-h-[260px] md:min-h-[340px] overflow-hidden bg-neutral-100">
                        <img src="{{ asset('images/berita-utama.png') }}"
                             alt="Kegiatan bersama" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent md:hidden"></div>
                        <span class="absolute top-4 left-4 bg-[#174A35] text-white text-[10px] sm:text-[11px] font-semibold tracking-wider uppercase px-3 py-1 rounded-full shadow-md">
                            Warta Utama
                        </span>
                    </div>

                    <div class="md:col-span-6 p-6 sm:p-8 md:p-10 flex flex-col justify-center">
                        <span class="text-xs font-semibold uppercase tracking-wider text-brand-accent mb-2">Majelis Wakil Cabang</span>
                        <h2 class="font-serif text-2xl sm:text-3xl font-medium text-neutral-900 leading-tight group-hover:text-[#174A35] transition-colors mb-3">
                            Kegiatan dan Informasi NU Ajibarang
                        </h2>
                        <p class="text-neutral-600 text-sm md:text-base leading-relaxed mb-6 font-light">
                            Ikuti informasi kegiatan, program kerja sosial kemasyarakatan, serta perkembangan organisasi Nahdlatul Ulama di Kecamatan Ajibarang secara berkala.
                        </p>
                        <a href="#daftar-berita" class="inline-flex items-center gap-2 text-sm font-semibold text-[#174A35] hover:gap-3 transition-all duration-300 w-fit">
                            <span>Jelajahi Berita Terbaru</span>
                            <span class="text-lg">↓</span>
                        </a>
                    </div>
                </div>
            </section>

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
                    @forelse ($berita as $item)
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
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 100) }}
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

    <!-- AOS & SIDEBAR TOGGLE SCRIPT -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi Animasi AOS
        AOS.init({
            once: true,
            duration: 800,
            easing: 'ease-out-cubic'
        });

        // Kontrol Buka/Tutup Sidebar di Layar HP (Mobile)
        const sidebar = document.getElementById('sidebar');
        const toggleSidebarBtn = document.getElementById('toggle-sidebar');
        const closeSidebarBtn = document.getElementById('close-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
        }

        toggleSidebarBtn?.addEventListener('click', openSidebar);
        closeSidebarBtn?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);
    </script>

</body>
</html>
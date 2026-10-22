<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MWC NU Kecamatan Ajibarang</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>

  <!-- AOS Animation CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

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
            kenburns: {
              '0%': { transform: 'scale(1)' },
              '100%': { transform: 'scale(1.08)' },
            },
            pulseGlow: {
              '0%, 100%': { opacity: '0.3' },
              '50%': { opacity: '0.7' },
            }
          },
          animation: {
            'ken-burns': 'kenburns 18s infinite alternate ease-in-out',
            'pulse-glow': 'pulseGlow 4s ease-in-out infinite',
          }
        }
      }
    };
  </script>
</head>

<body class="bg-[#FBFBF9] text-neutral-900 font-sans antialiased selection:bg-[#174A35] selection:text-white overflow-x-hidden">

  <!-- OVERLAY / BACKDROP MOBILE -->
  <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"></div>

  <!-- HEADER KHUSUS MOBILE (DILENGKAPI TOMBOL HAMBURGER) -->
  <header class="sticky top-0 z-30 flex items-center justify-between border-b border-brand-border bg-white px-4 py-3 md:hidden">
    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
      <img src="{{ asset('images/logo-nu.jpg') }}" alt="Logo NU" class="h-9 w-9 rounded-full object-contain border border-neutral-200">
      <div>
        <p class="text-sm font-bold text-[#174A35] leading-tight">MWC NU Ajibarang</p>
        <p class="text-[10px] text-brand-muted">Banyumas, Jawa Tengah</p>
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

      <!-- Berita -->
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

    <!-- Footer Sidebar / Akun Pengguna -->
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
        <a href="{{ route('login') }}" class="flex w-full items-center justify-center gap-2 rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-[#174A35] hover:bg-[#F8F7F2] transition-colors shadow-sm">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
          Login
        </a>
      @endauth
    </div>
  </aside>

  <!-- PEMBUNGKUS KONTEN UTAMA (MEMILIKI PADDING LEFT md:pl-64 AGAR TIDAK TERTUTUP SIDEBAR) -->
  <div class="md:pl-64 flex flex-col min-h-screen">

    <main class="flex-grow">

 
<!-- HERO / BANNER RESPONSIVE -->
<section class="relative flex items-center overflow-hidden bg-[#174A35] min-h-[460px] sm:min-h-[520px] md:min-h-[580px]">

  <!-- Background: gambar tetap utuh di HP -->
  <div
    class="absolute inset-0 bg-no-repeat bg-center bg-contain sm:bg-cover"
    style="background-image: url('{{ asset('images/banner-nu.jpg') }}');"
  ></div>

  <!-- Overlay agar teks tetap terbaca -->
  <div class="absolute inset-0 bg-gradient-to-b from-black/85 via-black/65 to-black/50 sm:bg-gradient-to-r sm:from-black/85 sm:via-black/60 sm:to-black/20"></div>

  <!-- Konten banner -->
  <div class="relative z-10 mx-auto w-full max-w-5xl px-4 py-10 sm:px-6 sm:py-16 md:py-24">
    <div class="flex max-w-2xl flex-col justify-center">

      <div data-aos="fade-down" data-aos-duration="800">
        <span class="mb-4 inline-flex max-w-full items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.12em] text-[#D9C28D] shadow-inner backdrop-blur-md sm:mb-6 sm:px-3.5 sm:text-xs sm:tracking-[0.2em]">
          <span class="h-2 w-2 shrink-0 rounded-full bg-[#D9C28D]"></span>
          <span class="whitespace-normal">Majelis Wakil Cabang Nahdlatul Ulama</span>
        </span>
      </div>

      <h1 data-aos="fade-right" data-aos-duration="800" data-aos-delay="200"
          class="mb-4 font-serif text-3xl font-medium leading-tight tracking-tight text-white drop-shadow-md sm:mb-6 sm:text-4xl sm:leading-[1.15] lg:text-[50px]">
        Merawat Tradisi,<br>
        <span class="font-normal italic text-[#F2E8CF] underline decoration-[#B69A5A]/50 underline-offset-4 sm:underline-offset-8">Menguatkan Umat</span>
      </h1>

      <p data-aos="fade-right" data-aos-duration="800" data-aos-delay="400"
         class="mb-6 max-w-xl text-sm font-light leading-relaxed text-white/90 drop-shadow sm:mb-8 sm:text-base">
        Media portal informasi resmi, warta keagamaan, dan agenda kegiatan MWC NU Kecamatan Ajibarang.
      </p>

      <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="600"
           class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center sm:gap-6">
        <a href="#berita"
           class="group inline-flex items-center justify-center gap-2 rounded-lg bg-[#174A35] px-5 py-3 text-center text-sm font-medium tracking-wide text-white shadow-lg transition-all duration-300 hover:bg-[#113928] sm:px-6">
          Baca Berita
          <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
          </svg>
        </a>

        <a href="#tentang-kami"
           class="group inline-flex items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/5 px-5 py-3 text-center text-sm font-medium text-white backdrop-blur-sm transition-all duration-300 hover:border-white/40 hover:bg-white/10 hover:text-[#D9C28D] sm:px-6">
          Tentang Kami
          <span class="transition-transform duration-300 group-hover:translate-x-1">→</span>
        </a>
      </div>

    </div>
  </div>

  <div class="pointer-events-none absolute inset-x-0 bottom-0 h-10 bg-gradient-to-t from-[#FBFBF9] to-transparent"></div>
</section>


      <!-- BERITA TERBARU -->
      <section class="bg-brand-surface border-y border-brand-border py-16 md:py-20" id="berita">
        <div class="max-w-5xl mx-auto px-6">

          <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10" data-aos="fade-up">
            <div>
              <span class="text-xs uppercase tracking-[0.2em] font-semibold text-brand-accent block mb-2">
                Kabar Terkini
              </span>
              <h2 class="font-serif text-3xl md:text-4xl text-[#174A35] font-medium tracking-tight">
                Berita Terbaru
              </h2>
            </div>

            <a href="{{ route('berita.index') }}" class="text-sm font-semibold text-[#174A35] hover:underline inline-flex items-center gap-1 group">
              Lihat Semua Berita <span class="group-hover:translate-x-1 transition-transform">→</span>
            </a>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($beritaTerbaru as $item)
              <article data-aos="fade-up" class="bg-white border border-brand-border rounded-xl overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">

                @if ($item->gambar)
                  <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="w-full h-48 object-cover" loading="lazy">
                @else
                  <div class="w-full h-48 bg-[#E5E0D5] flex items-center justify-center text-xs text-gray-500 font-medium">
                    Tidak ada gambar
                  </div>
                @endif

                <div class="p-6 flex flex-col flex-grow">
                  <time class="text-[11px] font-semibold text-brand-muted uppercase tracking-wider mb-2">
                    {{ $item->created_at?->format('d M Y') ?? 'Tanggal tidak tersedia' }}
                  </time>

                  <h3 class="font-serif text-lg font-medium mb-2 text-neutral-900 leading-snug hover:text-[#174A35] transition-colors">
                    <a href="{{ route('berita.show', $item) }}">{{ $item->judul }}</a>
                  </h3>

                  <p class="text-xs text-neutral-600 leading-relaxed mb-5 font-light">
                    {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 110) }}
                  </p>

                  <a href="{{ route('berita.show', $item) }}" class="mt-auto text-xs font-semibold text-[#174A35] hover:underline inline-flex items-center gap-1">
                    Baca selengkapnya <span>→</span>
                  </a>
                </div>
              </article>
            @empty
              <div class="md:col-span-3 py-12 text-center bg-white border border-dashed border-brand-border rounded-xl">
                <p class="text-sm text-gray-500">Belum ada berita yang diterbitkan.</p>
              </div>
            @endforelse
          </div>

        </div>
      </section>

      <!-- TENTANG KAMI -->
      <section class="max-w-5xl mx-auto px-6 py-16 md:py-20 overflow-hidden" id="tentang-kami">
        <div class="max-w-3xl" data-aos="fade-right">

          <span class="text-[12px] uppercase tracking-[0.2em] font-semibold text-brand-accent block mb-2">
            Profil Kelembagaan
          </span>

          <h2 class="font-serif text-3xl md:text-4xl text-[#174A35] font-medium tracking-tight mb-4">
            Tentang MWC NU Ajibarang
          </h2>

          <p class="text-neutral-700 text-base leading-relaxed mb-6 font-light">
            Majelis Wakil Cabang Nahdlatul Ulama (MWC NU) Kecamatan Ajibarang merupakan struktur kepengurusan di tingkat kecamatan Kabupaten Banyumas yang bertugas menjaga aqidah Ahlussunnah wal Jama’ah An-Nahdliyyah, membina kehidupan keagamaan, serta menyelenggarakan pelayanan sosial dan pemberdayaan umat secara mandiri dan berkesinambungan.
          </p>

          <a class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#174A35] hover:gap-2 transition-all duration-300" href="#">
            <span>Selengkapnya</span>
            <span>→</span>
          </a>

        </div>
      </section>

      <!-- STRUKTUR ORGANISASI -->
      <section class="bg-brand-surface border-t border-brand-border py-16 md:py-20" id="struktur-organisasi">
        <div class="max-w-5xl mx-auto px-6">

          <div class="text-center max-w-xl mx-auto mb-12" data-aos="fade-up">
            <h2 class="font-serif text-3xl md:text-4xl text-[#174A35] font-medium tracking-tight mb-2">
              Struktur Organisasi
            </h2>
            <p class="text-brand-muted text-sm">
              Mengenal perwakilan pengurus yang mengemban amanah jam'iyyah.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- RAIS SYURIYAH -->
            <div data-aos="zoom-in" data-aos-delay="100" class="bg-white border border-brand-border rounded-xl p-6 hover:shadow-lg hover:border-[#174A35]/40 transition-all duration-300 hover:-translate-y-1">
              <span class="text-[10px] font-semibold tracking-wider uppercase text-brand-accent mb-2 block">Syuriyah</span>
              <h3 class="font-serif text-lg font-medium text-neutral-900 mb-1">K.H. Abdurrohman Wahid</h3>
              <p class="text-xs text-brand-muted">Rais Syuriyah MWC NU</p>
            </div>

            <!-- KATIB SYURIYAH -->
            <div data-aos="zoom-in" data-aos-delay="200" class="bg-white border border-brand-border rounded-xl p-6 hover:shadow-lg hover:border-[#174A35]/40 transition-all duration-300 hover:-translate-y-1">
              <span class="text-[10px] font-semibold tracking-wider uppercase text-brand-accent mb-2 block">Syuriyah</span>
              <h3 class="font-serif text-lg font-medium text-neutral-900 mb-1">K. Ahmad Munawir, S.Ag.</h3>
              <p class="text-xs text-brand-muted">Katib Syuriyah</p>
            </div>

            <!-- KETUA TANFIDZIYAH -->
            <div data-aos="zoom-in" data-aos-delay="300" class="bg-white border border-brand-border rounded-lg p-6 hover:shadow-lg hover:border-[#174A35]/40 transition-all duration-300 hover:-translate-y-1">
              <span class="text-[10px] font-semibold tracking-wider uppercase text-brand-accent mb-2 block">Tanfidziyah</span>
              <h3 class="font-serif text-lg font-medium text-neutral-900 mb-1">H. Muhammad As'ad, M.Pd.</h3>
              <p class="text-xs text-brand-muted">Ketua Tanfidziyah</p>
            </div>

          </div>

          <div class="mt-10 text-center" data-aos="fade-up" data-aos-offset="50">
            <a class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#174A35] hover:gap-2.5 transition-all duration-300" href="{{ route('pengurus.index') }}">
              <span>Lihat Struktur Lengkap</span>
              <span>→</span>
            </a>
          </div>

        </div>
      </section>

      <!-- KARTU ANGGOTA CTA -->
      <section class="max-w-5xl mx-auto px-6 py-12">
        <div data-aos="flip-up" class="bg-[#174A35] text-white rounded-2xl p-8 md:p-10 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl relative overflow-hidden group">
          <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>

          <div class="max-w-xl relative z-10">
            <h3 class="font-serif text-2xl font-normal text-white mb-2">
              Bagian dari Keluarga Besar NU
            </h3>
            <p class="text-neutral-200 text-sm leading-relaxed font-light">
              Lengkapi dan perbarui data keanggotaan Anda untuk kemudahan akses layanan keorganisasian.
            </p>
          </div>

          <a class="relative z-10 inline-block px-6 py-3 bg-white text-[#174A35] hover:bg-brand-surface hover:scale-105 active:scale-95 text-xs font-bold uppercase tracking-wider rounded-lg transition-all duration-300 shadow-md" href="{{ route('kartu.index') ?? '#' }}">
            Kartu Anggota
          </a>
        </div>
      </section>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-brand-border pt-12 pb-8">
      <div class="max-w-5xl mx-auto px-6">

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-brand-border">

          <div class="md:col-span-5" data-aos="fade-right">
            <h4 class="font-serif text-lg text-[#174A35] font-semibold mb-2">
              MWC NU Kecamatan Ajibarang
            </h4>
            <p class="text-xs text-neutral-600 leading-relaxed max-w-sm">
              Wadah khidmah keagamaan dan kemasyarakatan Nahdlatul Ulama di tingkat kecamatan Ajibarang, Kabupaten Banyumas.
            </p>
          </div>

          <div class="md:col-span-3" data-aos="fade-up" data-aos-delay="100">
            <h5 class="text-xs uppercase font-semibold text-neutral-900 tracking-wider mb-3">Navigasi</h5>
            <ul class="space-y-2 text-xs text-neutral-600">
              <li><a class="hover:text-[#174A35] transition-colors" href="{{ route('home') }}">Beranda</a></li>
              <li><a class="hover:text-[#174A35] transition-colors" href="{{ route('berita.index') }}">Berita</a></li>
              <li><a class="hover:text-[#174A35] transition-colors" href="{{ route('pengurus.index') }}">Struktur Organisasi</a></li>
              <li><a class="hover:text-[#174A35] transition-colors" href="#tentang-kami">Tentang Kami</a></li>
            </ul>
          </div>

          <div class="md:col-span-4" data-aos="fade-up" data-aos-delay="200">
            <h5 class="text-xs uppercase font-semibold text-neutral-900 tracking-wider mb-3">Kontak &amp; Sekretariat</h5>
            <div class="space-y-2 text-xs text-neutral-600">
              <p>Gedung MWC NU Ajibarang, Jl. Raya Ajibarang - Wangon KM 1, Banyumas, Jawa Tengah 53163</p>
              <p class="pt-1">
                Email: <a class="text-neutral-800 hover:text-[#174A35] hover:underline" href="mailto:sekretariat@mwcnu-ajibarang.or.id">sekretariat@mwcnu-ajibarang.or.id</a>
              </p>
              <p>Kontak: (0281) 657-XXXX</p>
            </div>
          </div>

        </div>

        <div class="pt-6 text-center sm:text-left text-xs text-neutral-500">
          © {{ date('Y') }} Majelis Wakil Cabang Nahdlatul Ulama Kecamatan Ajibarang. All rights reserved.
        </div>

      </div>
    </footer>

  </div>

  <!-- AOS Animation Script -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    // Inisialisasi AOS
    AOS.init({
      once: true,
      duration: 800,
      offset: 80,
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
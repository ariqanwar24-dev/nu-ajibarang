
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

  <!-- NAVBAR -->
  <x-public-navbar />

  <!-- KONTEN UTAMA -->
  <div class="flex min-h-screen flex-col">
    <main class="flex-grow">

      <!-- HERO / BANNER RESPONSIF -->
      <section class="relative flex min-h-[520px] items-center overflow-hidden py-12 sm:min-h-[580px] sm:py-16 md:min-h-[640px] lg:min-h-[min(760px,calc(100svh-80px))] lg:py-20">

        <!-- Background image -->
        <div
          class="absolute inset-0 transform scale-100 bg-cover bg-no-repeat bg-[position:68%_center] transition-transform duration-1000 sm:bg-center lg:animate-ken-burns"
          style="background-image: url('{{ asset('images/banner-nu.jpg') }}');">
        </div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/65 to-black/35"></div>

        <!-- Decorative glow -->
        <div class="pointer-events-none absolute -left-24 -top-24 h-96 w-96 rounded-full bg-[#174A35]/40 blur-3xl animate-pulse-glow"></div>
        <div class="pointer-events-none absolute bottom-0 right-10 h-80 w-80 rounded-full bg-[#B69A5A]/20 blur-3xl animate-pulse-glow"></div>

        <!-- Hero content -->
        <div class="relative z-10 mx-auto w-full max-w-5xl px-5 sm:px-6 lg:px-8">
          <div class="flex max-w-2xl flex-col justify-center">

            <!-- Label -->
            <div data-aos="fade-down" data-aos-duration="800">
              <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3.5 py-1.5 text-[10px] font-semibold uppercase tracking-[0.15em] text-[#D9C28D] shadow-inner backdrop-blur-md sm:text-xs sm:tracking-[0.2em]">
                <span class="h-2 w-2 animate-ping rounded-full bg-[#D9C28D]"></span>
                Majelis Wakil Cabang Nahdlatul Ulama
              </span>
            </div>

            <!-- Responsive heading -->
            <h1
              data-aos="fade-right"
              data-aos-duration="800"
              data-aos-delay="200"
              class="mb-6 font-serif text-3xl font-medium leading-[1.15] tracking-tight text-white drop-shadow-md sm:text-4xl md:text-5xl lg:text-[50px]">

              Merawat Tradisi,<br>
              <span class="font-normal italic text-[#F2E8CF] underline decoration-[#B69A5A]/50 underline-offset-8">
                Menguatkan Umat
              </span>
            </h1>

            <!-- Description -->
            <p
              data-aos="fade-right"
              data-aos-duration="800"
              data-aos-delay="400"
              class="mb-8 max-w-xl text-sm font-light leading-relaxed text-white/90 drop-shadow sm:text-base md:text-lg">

              Media portal informasi resmi, warta keagamaan, dan agenda kegiatan MWC NU Kecamatan Ajibarang.
            </p>

            <!-- Button -->
            <div
              data-aos="fade-up"
              data-aos-duration="800"
              data-aos-delay="600"
              class="flex flex-wrap items-center gap-4 sm:gap-6">

              <a
                class="group relative inline-flex items-center justify-center overflow-hidden rounded-lg bg-[#174A35] px-6 py-3 text-sm font-medium tracking-wide text-white shadow-lg transition-all duration-300 hover:-translate-y-1 hover:bg-[#113928] hover:shadow-2xl"
                href="#berita">

                <span class="absolute inset-0 h-full w-full bg-white/10 opacity-0 transition-opacity group-hover:opacity-100"></span>

                <span class="relative z-10 flex items-center gap-2">
                  Baca Berita

                  <svg
                    class="h-4 w-4 transform transition-transform group-hover:translate-x-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                  </svg>
                </span>
              </a>

            </div>
          </div>
        </div>

        <!-- Bottom fade -->
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-10 bg-gradient-to-t from-[#FBFBF9] to-transparent"></div>

      </section>

      <!-- GAMBAR TAMBAHAN DI BAWAH HERO -->
      <section class="bg-[#FBFBF9] px-5 py-8 sm:px-6 md:py-12">
        <div class="mx-auto max-w-7xl" data-aos="fade-up">

          <img
            src="{{ asset('images/gambar-tambahan.png') }}"
            alt="ketua dan pengurus MWC NU Ajibarang"
            class="h-auto max-h-[520px] w-full rounded-xl object-cover shadow-md"
            loading="lazy">

        </div>
      </section>

      <!-- BERITA TERBARU -->
      <section class="bg-white py-14 md:py-20" id="berita">
        <div class="mx-auto max-w-7xl px-5 md:px-8">

          <div
            class="mb-7 flex flex-col gap-4 border-b-2 border-[#174A35] pb-4 sm:flex-row sm:items-end sm:justify-between"
            data-aos="fade-up">

            <div>
              <span class="mb-2 inline-block text-xs font-bold uppercase tracking-[0.18em] text-[#B3261E]">
                Kabar Terkini
              </span>

              <h2 class="text-3xl font-extrabold tracking-tight text-neutral-950 md:text-4xl">
                Berita <span class="text-[#174A35]">Terbaru</span>
              </h2>

              <p class="mt-2 max-w-xl text-sm text-neutral-600">
                Informasi dan kegiatan terbaru dari MWC NU Kecamatan Ajibarang.
              </p>
            </div>

            <a
              href="{{ route('berita.index') }}"
              class="inline-flex items-center gap-2 self-start text-sm font-bold text-[#174A35] hover:text-[#B3261E] sm:self-auto">

              Semua berita <span aria-hidden="true">→</span>
            </a>
          </div>

          @if ($beritaTerbaru->isNotEmpty())

            @php
              $beritaUtama = $beritaTerbaru->first();
              $beritaLainnya = $beritaTerbaru->skip(1);
            @endphp

            <div class="grid grid-cols-1 gap-7 lg:grid-cols-12 lg:gap-8">

              <!-- BERITA UTAMA -->
              <article class="group lg:col-span-7" data-aos="fade-up">

                <a
                  href="{{ route('berita.show', $beritaUtama) }}"
                  class="block overflow-hidden bg-neutral-100">

                  @if ($beritaUtama->gambar)

                    <img
                      src="{{ asset('storage/' . $beritaUtama->gambar) }}"
                      alt="{{ $beritaUtama->judul }}"
                      class="aspect-[16/9] w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                      loading="lazy">

                  @else

                    <div class="flex aspect-[16/9] w-full items-center justify-center bg-[#E8E8E4] text-sm text-neutral-500">
                      Tidak ada gambar berita
                    </div>

                  @endif

                </a>

                <div class="pt-4">

                  <span class="mb-3 inline-block bg-[#174A35] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white">
                    Berita Utama
                  </span>

                  <h3 class="text-2xl font-extrabold leading-tight tracking-tight text-neutral-950 transition-colors group-hover:text-[#174A35] md:text-3xl">
                    <a href="{{ route('berita.show', $beritaUtama) }}">
                      {{ $beritaUtama->judul }}
                    </a>
                  </h3>

                  <p class="mt-3 text-sm leading-7 text-neutral-600 md:text-base">
                    {{ \Illuminate\Support\Str::limit(strip_tags($beritaUtama->isi), 220) }}
                  </p>

                  <div class="mt-4 flex items-center gap-2 text-xs font-medium text-neutral-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#B3261E]"></span>
                    {{ $beritaUtama->created_at?->format('d M Y') ?? 'Tanggal tidak tersedia' }}
                  </div>

                </div>
              </article>

              <!-- BERITA LAINNYA -->
              <div class="divide-y divide-neutral-200 border-y border-neutral-200 lg:col-span-5 lg:border-t-0">

                @forelse ($beritaLainnya as $item)

                  <article
                    class="group grid grid-cols-1 gap-4 py-5 first:pt-0 sm:grid-cols-5"
                    data-aos="fade-up">

                    <a
                      href="{{ route('berita.show', $item) }}"
                      class="block overflow-hidden bg-neutral-100 sm:col-span-2">

                      @if ($item->gambar)

                        <img
                          src="{{ asset('storage/' . $item->gambar) }}"
                          alt="{{ $item->judul }}"
                          class="aspect-[4/3] h-full w-full object-cover transition duration-500 group-hover:scale-105"
                          loading="lazy">

                      @else

                        <div class="flex aspect-[4/3] h-full items-center justify-center bg-[#E8E8E4] text-center text-xs text-neutral-500">
                          Tidak ada gambar
                        </div>

                      @endif

                    </a>

                    <div class="sm:col-span-3">

                      <p class="mb-2 text-[10px] font-bold uppercase tracking-wider text-[#B3261E]">
                        Berita NU Ajibarang
                      </p>

                      <h3 class="text-lg font-bold leading-snug text-neutral-950 transition-colors group-hover:text-[#174A35]">
                        <a href="{{ route('berita.show', $item) }}">
                          {{ $item->judul }}
                        </a>
                      </h3>

                      <p class="mt-2 text-sm leading-6 text-neutral-600">
                        {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 95) }}
                      </p>

                      <time class="mt-3 block text-xs text-neutral-500">
                        {{ $item->created_at?->format('d M Y') ?? 'Tanggal tidak tersedia' }}
                      </time>

                    </div>
                  </article>

                @empty

                  <div class="py-8 text-sm text-neutral-500">
                    Belum ada berita lainnya.
                  </div>

                @endforelse

              </div>
            </div>

          @else

            <div class="border-y border-neutral-200 py-14 text-center">
              <p class="text-base font-semibold text-neutral-700">
                Belum ada berita yang diterbitkan.
              </p>

              <p class="mt-2 text-sm text-neutral-500">
                Berita terbaru akan muncul di bagian ini.
              </p>
            </div>

          @endif

          <div class="mt-8 border-t border-neutral-200 pt-5 text-right">
            <a
              href="{{ route('berita.index') }}"
              class="inline-flex items-center gap-2 border border-[#174A35] px-5 py-3 text-sm font-bold text-[#174A35] transition hover:bg-[#174A35] hover:text-white">

              Jelajahi semua berita <span aria-hidden="true">→</span>
            </a>
          </div>

        </div>
      </section>

      <!-- KARTU ANGGOTA CTA -->
      <section class="mx-auto max-w-5xl px-6 py-12">

        <div
          data-aos="flip-up"
          class="group relative flex flex-col justify-between gap-6 overflow-hidden rounded-2xl bg-[#174A35] p-8 text-white shadow-xl md:flex-row md:items-center md:p-10">

          <div class="pointer-events-none absolute -bottom-10 -right-10 h-48 w-48 rounded-full bg-white/5 blur-xl transition-transform duration-700 group-hover:scale-150"></div>

          <div class="relative z-10 max-w-xl">

            <h3 class="mb-2 font-serif text-2xl font-normal text-white">
              Bagian dari Keluarga Besar NU
            </h3>

            <p class="text-sm font-light leading-relaxed text-neutral-200">
              Lengkapi dan perbarui data keanggotaan Anda untuk kemudahan akses layanan keorganisasian.
            </p>

          </div>

          <a
            class="relative z-10 inline-block rounded-lg bg-white px-6 py-3 text-xs font-bold uppercase tracking-wider text-[#174A35] shadow-md transition-all duration-300 hover:scale-105 hover:bg-brand-surface active:scale-95"
            href="{{ route('kartu.index') }}">

            Kartu Anggota
          </a>

        </div>
      </section>

    </main>

    <!-- FOOTER -->
    <footer class="border-t border-brand-border bg-white pb-8 pt-12">

      <div class="mx-auto max-w-5xl px-6">

        <div class="grid grid-cols-1 gap-8 border-b border-brand-border pb-10 md:grid-cols-12">

          <!-- Identitas -->
          <div class="md:col-span-5" data-aos="fade-right">

            <h4 class="mb-2 font-serif text-lg font-semibold text-[#174A35]">
              MWC NU Kecamatan Ajibarang
            </h4>

            <p class="max-w-sm text-xs leading-relaxed text-neutral-600">
              Wadah khidmah keagamaan dan kemasyarakatan Nahdlatul Ulama di tingkat kecamatan Ajibarang, Kabupaten Banyumas.
            </p>

          </div>

          <!-- Navigasi -->
          <div class="md:col-span-3" data-aos="fade-up" data-aos-delay="100">

            <h5 class="mb-3 text-xs font-semibold uppercase tracking-wider text-neutral-900">
              Navigasi
            </h5>

            <ul class="space-y-2 text-xs text-neutral-600">
              <li>
                <a class="transition-colors hover:text-[#174A35]" href="{{ route('home') }}">
                  Beranda
                </a>
              </li>

              <li>
                <a class="transition-colors hover:text-[#174A35]" href="{{ route('berita.index') }}">
                  Berita
                </a>
              </li>

              <li>
                <a class="transition-colors hover:text-[#174A35]" href="{{ route('pengurus.index') }}">
                  Struktur Organisasi
                </a>
              </li>

            </ul>

          </div>

          <!-- Kontak -->
          <div class="md:col-span-4" data-aos="fade-up" data-aos-delay="200">

            <h5 class="mb-3 text-xs font-semibold uppercase tracking-wider text-neutral-900">
              Kontak &amp; Sekretariat
            </h5>

            <div class="space-y-2 text-xs text-neutral-600">

              <p>
                Gedung MWC NU Ajibarang, Jl. Raya Ajibarang - Wangon KM 1, Banyumas, Jawa Tengah 53163
              </p>

              <p class="pt-1">
                Email:
                <a
                  class="text-neutral-800 hover:text-[#174A35] hover:underline"
                  href="mailto:sekretariat@mwcnu-ajibarang.or.id">

                  sekretariat@mwcnu-ajibarang.or.id
                </a>
              </p>

              <p>Kontak: (0281) 657-XXXX</p>

            </div>
          </div>

        </div>

        <!-- Copyright -->
        <div class="pt-6 text-center text-xs text-neutral-500 sm:text-left">
          © {{ date('Y') }} RPL SMK Ma'arif NU Ajibarang.
        </div>

      </div>
    </footer>

  </div>

  <!-- AOS Animation Script -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <script>
    AOS.init({
      once: true,
      duration: 800,
      offset: 80,
      easing: 'ease-out-cubic'
    });
  </script>

</body>
</html>

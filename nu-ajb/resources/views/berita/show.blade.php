
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $berita->judul }} | MWC NU Ajibarang</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .article-content {
            overflow-wrap: break-word;
            line-height: 1.9;
        }

        .article-content p {
            margin-bottom: 1.25rem;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
            margin: 1.5rem 0;
        }
    </style>
</head>

<body class="bg-[#F8FAF8] text-neutral-800">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 border-b border-neutral-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">

            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img
                    src="{{ asset('images/logo-nu.jpg') }}"
                    alt="Logo NU"
                    class="h-11 w-11 rounded-full object-contain"
                >

                <div>
                    <p class="text-sm font-extrabold text-[#174A35]">
                        MWC NU Ajibarang
                    </p>
                    <p class="text-xs text-neutral-500">
                        Majelis Wakil Cabang Nahdlatul Ulama
                    </p>
                </div>
            </a>

            <a
                href="{{ route('berita.index') }}"
                class="rounded-lg border border-neutral-200 px-4 py-2 text-sm font-semibold transition hover:border-[#174A35] hover:bg-[#174A35] hover:text-white"
            >
                Kembali ke Berita
            </a>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="mx-auto max-w-4xl px-5 pt-8">
        <div class="flex flex-wrap items-center gap-2 text-sm text-neutral-500">
            <a href="{{ route('home') }}" class="hover:text-[#174A35]">
                Beranda
            </a>

            <span>/</span>

            <a href="{{ route('berita.index') }}" class="hover:text-[#174A35]">
                Berita
            </a>

            <span>/</span>

            <span class="text-[#174A35]">Detail Berita</span>
        </div>
    </div>

    <!-- Article -->
    <main class="mx-auto max-w-4xl px-5 pb-16 pt-8">

        <article class="overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm">

            <!-- Article Header -->
            <header class="px-6 pb-6 pt-8 sm:px-10 sm:pt-10">

                <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1.5 text-xs font-bold text-[#174A35]">
                    <span class="h-2 w-2 rounded-full bg-[#174A35]"></span>
                    BERITA NU AJIBARANG
                </span>

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
                            <circle cx="12" cy="12" r="9" stroke-width="1.8"/>
                            <path stroke-linecap="round" stroke-width="1.8" d="M12 7v5l3 2"/>
                        </svg>

                        Informasi Organisasi
                    </span>
                </div>
            </header>

            <!-- Featured Image -->
            @if ($berita->gambar)
                <div class="px-6 sm:px-10">
                    <img
                        src="{{ asset('storage/' . $berita->gambar) }}"
                        alt="{{ $berita->judul }}"
                        class="max-h-[500px] w-full rounded-xl object-cover"
                    >
                </div>
            @endif

            <!-- Article Content -->
            <div class="px-6 py-8 sm:px-10 sm:py-10">
                <div class="article-content text-base text-neutral-700">
                    {!! nl2br(e(strip_tags($berita->isi))) !!}
                </div>

                <div class="mt-10 border-t border-neutral-200 pt-6">
                    <a
                        href="{{ route('berita.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#174A35] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#103725]"
                    >
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
            <p class="font-semibold text-[#174A35]">
                MWC NU Kecamatan Ajibarang
            </p>

            <p>
                &copy; {{ date('Y') }} MWC NU Ajibarang. Seluruh hak cipta dilindungi.
            </p>
        </div>
    </footer>

</body>
</html>

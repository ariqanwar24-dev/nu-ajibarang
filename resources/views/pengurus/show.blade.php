<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pengurus->nama }} | Struktur Organisasi MWC NU Ajibarang</title>

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

    <main class="mx-auto max-w-7xl px-5 py-10 sm:px-6 sm:py-14 lg:px-8">
        <nav class="mb-7 flex flex-wrap items-center gap-2 text-xs text-brand-muted" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition-colors hover:text-brand">Beranda</a>
            <span>/</span>
            <a href="{{ route('pengurus.index') }}" class="transition-colors hover:text-brand">Struktur Organisasi</a>
            <span>/</span>
            <span class="font-semibold text-brand">{{ $pengurus->nama }}</span>
        </nav>

        <div class="grid gap-8 lg:grid-cols-[minmax(280px,380px)_minmax(0,1fr)] lg:items-start">
            <div class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                <div class="relative h-80 overflow-hidden bg-brand-surface sm:h-96 lg:h-auto lg:aspect-[5/6]">
                    @if ($pengurus->foto_url)
                        <img src="{{ $pengurus->foto_url }}"
                             alt="Foto {{ $pengurus->nama }}"
                             class="h-full w-full object-cover object-top"
                             decoding="async">
                    @else
                        <div class="flex h-full flex-col items-center justify-center bg-gradient-to-br from-brand via-brand-dark to-[#0A2A1D] px-6 text-center">
                            <span class="flex h-28 w-28 items-center justify-center rounded-full border border-white/15 bg-white/10 font-serif text-6xl font-medium text-white">
                                {{ mb_strtoupper(mb_substr($pengurus->nama, 0, 1)) }}
                            </span>
                            <span class="mt-6 text-[10px] font-bold uppercase tracking-[0.24em] text-white/55">
                                Foto belum tersedia
                            </span>
                        </div>
                    @endif

                    @if ($pengurus->is_demo)
                        <span class="absolute left-4 top-4 rounded-full border border-white/20 bg-black/45 px-3 py-1 text-[9px] font-extrabold uppercase tracking-[0.16em] text-white backdrop-blur">
                            Data contoh
                        </span>
                    @endif
                </div>

                <div class="border-t border-brand-border px-5 py-4">
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-muted">Terakhir diperbarui</p>
                    <p class="mt-1 text-xs font-semibold text-slate-700">{{ $pengurus->updated_at?->format('d/m/Y H:i') ?? '-' }}</p>
                </div>
            </div>

            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-brand/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-brand">
                        {{ $pengurus->kategoriLabel() }}
                    </span>

                    @if ($pengurus->banomName())
                        <span class="rounded-full border border-brand-border bg-white px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-brand-muted">
                            {{ $pengurus->banomName() }}
                        </span>
                    @endif
                </div>

                <p class="mt-6 text-xs font-extrabold uppercase tracking-[0.2em] text-brand-accent">
                    {{ $pengurus->jabatan ?: 'Pengurus' }}
                </p>

                <h1 class="mt-2 font-serif text-4xl font-medium leading-tight tracking-tight text-neutral-900 sm:text-5xl">
                    {{ $pengurus->nama }}
                </h1>

                <div class="mt-6 h-1 w-20 rounded-full bg-brand-accent"></div>

                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-brand-border bg-white p-5">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-muted">Jabatan</p>
                        <p class="mt-2 text-sm font-bold text-slate-800">{{ $pengurus->jabatan ?: 'Belum diisi' }}</p>
                    </div>

                    <div class="rounded-2xl border border-brand-border bg-white p-5">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-muted">Kategori</p>
                        <p class="mt-2 text-sm font-bold text-slate-800">{{ $pengurus->kategoriLabel() }}</p>
                    </div>
                </div>


                <div class="mt-8 rounded-2xl border border-brand-border bg-white p-5 sm:p-6">
                    <h2 class="font-serif text-xl font-semibold text-brand">Informasi kepengurusan</h2>
                    <p class="mt-3 text-sm leading-7 text-brand-muted">
                        Data ini dikelola melalui dashboard admin MWC NU Ajibarang. Untuk informasi susunan
                        pengurus resmi atau klarifikasi data, silakan hubungi sekretariat organisasi.
                    </p>
                </div>

                <div class="mt-7 flex flex-wrap items-center gap-3">
                    <a href="{{ route('pengurus.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
                        <span aria-hidden="true">←</span>
                        Kembali ke Struktur Organisasi
                    </a>

                    @auth
                        @if (auth()->user()->isActive() && auth()->user()->isAdmin())
                            <a href="{{ route('admin.pengurus.edit', $pengurus) }}"
                               class="inline-flex items-center gap-2 rounded-xl border border-brand-border bg-white px-5 py-3 text-sm font-bold text-brand transition hover:border-brand hover:bg-brand/5">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                Edit data
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.pengurus.destroy', $pengurus) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus data pengurus ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-bold text-red-700 transition hover:bg-red-100">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                    Hapus
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </main>

    <footer class="border-t border-brand-border bg-white py-8">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-5 text-xs text-brand-muted sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p class="font-semibold text-brand">MWC NU Kecamatan Ajibarang</p>
            <p>© {{ date('Y') }} MWC NU Kecamatan Ajibarang. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

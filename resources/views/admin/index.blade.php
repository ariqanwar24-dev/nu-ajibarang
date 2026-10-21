<x-admin-layout title="Dashboard Admin">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-brand">MWC NU Ajibarang</p>
                <h1 class="mt-1 font-serif text-2xl font-semibold tracking-tight text-brand-deeper sm:text-3xl">
                    Dashboard Admin
                </h1>
                <p class="mt-1.5 max-w-2xl text-sm text-brand-muted">
                    Assalamu'alaikum, <span class="font-bold text-slate-700">{{ auth()->user()->name ?? 'Administrator' }}</span>.
                    Ringkasan data organisasi ditampilkan langsung dari basis data.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.berita.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-brand px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>
                    </svg>
                    Tulis Berita
                </a>
                <a href="{{ route('admin.anggota.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-brand-border bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:border-brand/40 hover:text-brand">
                    Verifikasi Anggota
                    @if ($anggotaMenunggu > 0)
                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-extrabold text-amber-800">{{ $anggotaMenunggu }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.pengguna.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-brand-border bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:border-brand/40 hover:text-brand">
                    Persetujuan Akun
                    @if ($penggunaMenunggu > 0)
                        <span class="rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-extrabold text-rose-800">{{ $penggunaMenunggu }}</span>
                    @endif
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes dashRise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        .dash-rise { animation: dashRise .55s cubic-bezier(.2, .7, .2, 1) both; }
        .dash-delay-1 { animation-delay: .06s; }
        .dash-delay-2 { animation-delay: .12s; }
        .dash-delay-3 { animation-delay: .18s; }
        .stat-tile { transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease; }
        .stat-tile:hover { transform: translateY(-3px); box-shadow: 0 18px 35px -24px rgba(6, 78, 59, .45); }
        .bar-fill { width: 0; transition: width 1s cubic-bezier(.22, 1, .36, 1); }
        @media (prefers-reduced-motion: reduce) {
            .dash-rise, .bar-fill { animation: none; transition: none; }
            .stat-tile { transition: none; }
        }
    </style>

    <div class="mx-auto max-w-[1500px] space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        @php
            $statistik = [
                [
                    'label' => 'Anggota Disetujui',
                    'nilai' => $anggotaDisetujui,
                    'catatan' => 'Anggota resmi terverifikasi',
                    'route' => route('admin.anggota.index'),
                    'warna' => 'emerald',
                ],
                [
                    'label' => 'Pendaftaran Pending',
                    'nilai' => $anggotaMenunggu,
                    'catatan' => 'Perlu ditinjau admin',
                    'route' => route('admin.anggota.index'),
                    'warna' => 'amber',
                ],
                [
                    'label' => 'Desa Sudah Ada Anggota',
                    'nilai' => $desaDenganAnggota,
                    'catatan' => 'Dari '.$desas->count().' desa',
                    'route' => '#statistik-desa',
                    'warna' => 'sky',
                ],
                [
                    'label' => 'Desa Belum Ada Anggota',
                    'nilai' => $desaTanpaAnggota,
                    'catatan' => 'Belum memiliki anggota resmi',
                    'route' => '#statistik-desa',
                    'warna' => 'rose',
                ],
                [
                    'label' => 'Total Berita',
                    'nilai' => $ringkasanBerita['total'],
                    'catatan' => $ringkasanBerita['published'].' terbit · '.$ringkasanBerita['pending'].' menunggu review',
                    'route' => route('admin.berita.index'),
                    'warna' => 'teal',
                ],
                [
                    'label' => 'Total Pengurus',
                    'nilai' => $jumlahPengurus,
                    'catatan' => 'Data pada struktur organisasi',
                    'route' => route('pengurus.index'),
                    'warna' => 'indigo',
                ],
            ];

            $warnaKartu = [
                'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' => 'ring-emerald-100'],
                'teal' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'ring' => 'ring-teal-100'],
                'sky' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'ring' => 'ring-sky-100'],
                'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'ring' => 'ring-amber-100'],
                'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'ring' => 'ring-indigo-100'],
                'rose' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'ring' => 'ring-rose-100'],
            ];

            $statusAnggota = [
                'pending' => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-800'],
                'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-800'],
                'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700'],
            ];

            $labelStatusBerita = [
                \App\Models\Berita::STATUS_DRAFT => 'Draft',
                \App\Models\Berita::STATUS_PENDING => 'Menunggu Review',
                \App\Models\Berita::STATUS_PUBLISHED => 'Terbit',
                \App\Models\Berita::STATUS_REJECTED => 'Ditolak',
            ];
            $kelasStatusBerita = [
                \App\Models\Berita::STATUS_DRAFT => 'bg-slate-200 text-slate-700',
                \App\Models\Berita::STATUS_PENDING => 'bg-amber-100 text-amber-800',
                \App\Models\Berita::STATUS_PUBLISHED => 'bg-emerald-100 text-emerald-800',
                \App\Models\Berita::STATUS_REJECTED => 'bg-red-100 text-red-700',
            ];
        @endphp

        {{-- KARTU STATISTIK --}}
        <section class="dash-rise grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            @foreach ($statistik as $item)
                @php $warna = $warnaKartu[$item['warna']]; @endphp
                <div class="stat-tile rounded-2xl border border-brand-border bg-white p-4 shadow-sm ring-1 ring-transparent hover:border-brand/20">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">{{ $item['label'] }}</p>
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $warna['bg'] }} {{ $warna['text'] }} ring-1 {{ $warna['ring'] }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h16M7 16v-4m4 4V8m4 8v-6m4 6V6"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2.5 text-3xl font-black tracking-tight text-slate-900">
                        <span class="count-up" data-count="{{ $item['nilai'] }}">0</span>
                    </p>
                    @if ($item['route'])
                        <a href="{{ $item['route'] }}" class="mt-1 inline-block text-[11px] font-bold text-brand hover:underline">
                            {{ $item['catatan'] }} →
                        </a>
                    @else
                        <p class="mt-1 text-[11px] text-slate-500">{{ $item['catatan'] }}</p>
                    @endif
                </div>
            @endforeach
        </section>

        {{-- STATUS PUBLIKASI BERITA --}}
        <section class="dash-rise dash-delay-1 overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                <div>
                    <h2 class="text-lg font-black tracking-tight text-slate-900">Status Publikasi Berita</h2>
                    <p class="mt-0.5 text-xs text-brand-muted">
                        @if (auth()->user()->isSuperAdmin())
                            Ringkasan seluruh berita pada sistem.
                        @else
                            Ringkasan berita yang Anda kelola.
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.berita.index') }}"
                   class="rounded-lg border border-brand-border px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-brand hover:text-brand">
                    Buka Kelola Berita →
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 p-4 sm:p-5 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">Draft</p>
                        <span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-slate-800">{{ $ringkasanBerita['draft'] }}</p>
                    <p class="mt-1 text-[11px] text-slate-500">Belum diajukan</p>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-amber-700">Menunggu Review</p>
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-amber-800">{{ $ringkasanBerita['pending'] }}</p>
                    <p class="mt-1 text-[11px] text-amber-700/80">Belum tampil publik</p>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-emerald-700">Terbit</p>
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-emerald-800">{{ $ringkasanBerita['published'] }}</p>
                    <p class="mt-1 text-[11px] text-emerald-700/80">Tampil di halaman publik</p>
                </div>
                <div class="rounded-2xl border border-red-200 bg-red-50/70 p-4">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-red-700">Ditolak</p>
                        <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    </div>
                    <p class="mt-2 text-3xl font-black text-red-800">{{ $ringkasanBerita['rejected'] }}</p>
                    <p class="mt-1 text-[11px] text-red-700/80">Perlu diperbaiki</p>
                </div>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(320px,.85fr)]">
            <div class="space-y-6">
                {{-- BERITA TERBARU --}}
                <section class="dash-rise dash-delay-1 overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                        <div>
                            <h2 class="text-lg font-black tracking-tight text-slate-900">Berita Terbaru</h2>
                            <p class="mt-0.5 text-xs text-brand-muted">
                                @if (auth()->user()->isSuperAdmin())
                                    Lima berita terbaru dari seluruh admin.
                                @else
                                    Lima berita terbaru yang Anda kelola.
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('admin.berita.index') }}"
                           class="rounded-lg border border-brand-border px-3 py-1.5 text-xs font-bold text-slate-600 transition hover:border-brand hover:text-brand">
                            Kelola semua →
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($beritaTerbaru as $item)
                            <article class="flex items-center gap-3 px-4 py-3.5 transition hover:bg-slate-50/70 sm:gap-4 sm:px-6">
                                <div class="h-14 w-20 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                    @if ($item->gambar)
                                        <img src="{{ asset('storage/'.$item->gambar) }}" alt="Sampul {{ $item->judul }}"
                                             class="h-full w-full object-cover" loading="lazy">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-[9px] font-bold uppercase tracking-wide text-slate-400">
                                            Tanpa foto
                                        </div>
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span @class([
                                            'rounded-full px-2 py-0.5 text-[9.5px] font-extrabold uppercase tracking-wide',
                                            $kelasStatusBerita[$item->status] ?? $kelasStatusBerita[\App\Models\Berita::STATUS_DRAFT],
                                        ])>
                                            {{ $labelStatusBerita[$item->status] ?? 'Draft' }}
                                        </span>
                                        @if ($item->kategori)
                                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[9.5px] font-bold text-amber-800">{{ $item->kategori }}</span>
                                        @endif
                                    </div>
                                    <h3 class="mt-1 truncate text-sm font-bold text-slate-800">{{ $item->judul }}</h3>
                                    <p class="mt-0.5 text-[11px] text-slate-500">
                                        {{ $item->penulis ?: ($item->user?->name ?? 'Redaksi') }}
                                        · {{ $item->created_at?->translatedFormat('d M Y') ?? '—' }}
                                    </p>
                                </div>

                                <div class="flex shrink-0 items-center gap-1.5">
                                    <a href="{{ route('admin.berita.edit', $item) }}"
                                       class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-[11px] font-bold text-amber-800 hover:bg-amber-100">
                                        Edit
                                    </a>
                                    <a href="{{ route('berita.show', $item) }}"
                                       class="hidden rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-bold text-slate-700 hover:bg-slate-200 sm:block">
                                        Lihat
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="px-6 py-12 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-brand">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2M7 16h6M7 8h6v4H7V8Z"/>
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-bold text-slate-700">Belum ada berita</p>
                                <p class="mt-1 text-xs text-slate-500">Mulai dengan menulis berita pertama.</p>
                                <a href="{{ route('admin.berita.create') }}"
                                   class="mt-4 inline-flex rounded-xl bg-brand px-4 py-2 text-xs font-bold text-white hover:bg-brand-dark">
                                    + Tambah Berita
                                </a>
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- GRAFIK DESA --}}
                <section class="dash-rise dash-delay-2 rounded-3xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-black tracking-tight text-slate-900">Sebaran Anggota Resmi per Desa</h2>
                            <p class="mt-0.5 text-xs text-brand-muted">Jumlah anggota berstatus disetujui pada setiap desa, dihitung langsung dari basis data.</p>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[9.5px] font-extrabold uppercase tracking-wider text-emerald-800">
                            Data resmi
                        </span>
                    </div>

                    @if ($desas->isNotEmpty())
                        @php $maxAnggota = max(1, (int) $desas->max('approved_count')); @endphp
                        <div class="mt-5 grid gap-x-8 gap-y-3.5 sm:grid-cols-2">
                            @foreach ($desas as $desa)
                                <div>
                                    <div class="mb-1 flex items-center justify-between gap-2">
                                        <span class="truncate text-xs font-semibold text-slate-600">{{ $desa->nama }}</span>
                                        <span class="shrink-0 text-[11px] font-extrabold tabular-nums text-brand">{{ number_format($desa->approved_count) }}</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                        <div class="bar-fill h-full rounded-full bg-gradient-to-r from-brand to-teal-500"
                                             data-width="{{ round(($desa->approved_count / $maxAnggota) * 100, 2) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-4 text-[10.5px] leading-relaxed text-slate-400">
                            Grafik menampilkan seluruh desa, termasuk desa dengan jumlah anggota nol. Anggota lama yang belum memiliki data desa
                            tidak dihitung per desa sampai datanya dilengkapi.
                        </p>
                    @else
                        <p class="mt-5 rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                            Belum ada data desa. Jalankan <span class="font-mono text-xs">php artisan db:seed --class=DesaSeeder</span>.
                        </p>
                    @endif
                </section>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="space-y-6">
                {{-- AKTIVITAS ANGGOTA --}}
                <section class="dash-rise dash-delay-2 rounded-3xl border border-brand-border bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <h2 class="text-base font-black tracking-tight text-slate-900">Aktivitas Terbaru</h2>
                            <p class="mt-0.5 text-[11px] text-brand-muted">Pengajuan anggota terakhir.</p>
                        </div>
                        <span class="rounded-full bg-emerald-50 px-2 py-1 text-[10px] font-extrabold text-emerald-700">
                            {{ $anggotaDisetujui }} resmi
                        </span>
                    </div>

                    <div class="mt-4 space-y-1">
                        @forelse ($anggotaTerbaru as $anggota)
                            @php $status = $statusAnggota[$anggota->status_verifikasi] ?? $statusAnggota['pending']; @endphp
                            <div class="flex items-center gap-3 rounded-xl px-1 py-2">
                                <div class="h-9 w-9 shrink-0 overflow-hidden rounded-full bg-emerald-50">
                                    @if ($anggota->foto)
                                        <img src="{{ asset('storage/'.$anggota->foto) }}" alt="Foto {{ $anggota->nama_anggota }}"
                                             class="h-full w-full object-cover" loading="lazy">
                                    @else
                                        <span class="flex h-full w-full items-center justify-center text-[11px] font-extrabold text-emerald-800">
                                            {{ mb_substr($anggota->nama_anggota, 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-bold text-slate-800">{{ $anggota->nama_anggota }}</p>
                                    <p class="truncate text-[10.5px] text-slate-500">
                                        {{ $anggota->created_at?->diffForHumans() ?? '—' }}
                                        · {{ $anggota->desa?->nama ?? 'Desa belum diisi' }}
                                    </p>
                                </div>
                                <span class="shrink-0 rounded-full px-2 py-0.5 text-[9.5px] font-extrabold {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </div>
                        @empty
                            <p class="rounded-xl border border-dashed border-slate-200 px-3 py-6 text-center text-xs text-slate-500">
                                Belum ada pengajuan anggota.
                            </p>
                        @endforelse
                    </div>

                    <a href="{{ route('admin.anggota.index') }}"
                       class="mt-3 block rounded-xl bg-slate-50 px-3 py-2.5 text-center text-xs font-bold text-slate-700 transition hover:bg-emerald-50 hover:text-brand">
                        Buka halaman verifikasi →
                    </a>
                </section>

                {{-- RINGKASAN ORGANISASI --}}
                <section class="dash-rise dash-delay-3 overflow-hidden rounded-3xl bg-brand p-5 text-white shadow-lg">
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-brand-accent">Ringkasan Organisasi</p>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <span class="text-xs text-emerald-50/80">Anggota resmi</span>
                            <strong class="text-lg">{{ $anggotaDisetujui }}</strong>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <span class="text-xs text-emerald-50/80">Desa dengan anggota</span>
                            <strong class="text-lg">{{ $desaDenganAnggota }}</strong>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <span class="text-xs text-emerald-50/80">Desa belum ada anggota</span>
                            <strong class="text-lg">{{ $desaTanpaAnggota }}</strong>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <span class="text-xs text-emerald-50/80">Berita terbit</span>
                            <strong class="text-lg">{{ $ringkasanBerita['published'] }}</strong>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <span class="text-xs text-emerald-50/80">Berita menunggu review</span>
                            <strong class="text-lg text-amber-300">{{ $ringkasanBerita['pending'] }}</strong>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-emerald-50/80">Pendaftaran pending</span>
                            <strong class="text-lg text-amber-300">{{ $anggotaMenunggu }}</strong>
                        </div>
                    </div>
                    <a href="{{ route('admin.anggota.create') }}"
                       class="mt-4 block rounded-xl bg-white/10 px-3 py-2.5 text-center text-xs font-bold text-white transition hover:bg-white/20">
                        + Tambah Anggota
                    </a>
                </section>

                {{-- AKSI CEPAT --}}
                <section class="dash-rise dash-delay-3 rounded-3xl border border-brand-border bg-white p-5 shadow-sm">
                    <h2 class="text-base font-black tracking-tight text-slate-900">Aksi Cepat</h2>
                    <div class="mt-3 grid gap-2">
                        <a href="{{ route('admin.berita.create') }}"
                           class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-3 text-xs font-bold text-slate-700 transition hover:border-brand/20 hover:bg-emerald-50 hover:text-brand">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-brand shadow-sm">+</span>
                            Tulis Berita Baru
                        </a>
                        <a href="{{ route('admin.anggota.create') }}"
                           class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-3 text-xs font-bold text-slate-700 transition hover:border-brand/20 hover:bg-emerald-50 hover:text-brand">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-brand shadow-sm">+</span>
                            Tambah Data Anggota
                        </a>
                        <a href="{{ route('admin.pengurus.index') }}"
                           class="flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50 px-3.5 py-3 text-xs font-bold text-slate-700 transition hover:border-brand/20 hover:bg-emerald-50 hover:text-brand">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-brand shadow-sm">⚙</span>
                            Kelola Struktur Pengurus
                        </a>
                    </div>
                </section>
            </div>
        </div>

        {{-- TABEL STATISTIK DESA --}}
        <section id="statistik-desa" class="dash-rise dash-delay-3 rounded-3xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">
            @php
                $desaMenunggu = $desas
                    ->filter(fn ($desa) => $desa->approved_count === 0 && $desa->pending_count > 0)
                    ->count();
            @endphp

            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-black tracking-tight text-slate-900">Statistik Anggota per Desa</h2>
                    <p class="mt-0.5 max-w-2xl text-xs leading-5 text-brand-muted">
                        Seluruh 15 desa Kecamatan Ajibarang. Angka anggota resmi hanya menghitung pendaftaran yang sudah disetujui;
                        pendaftaran pending ditampilkan terpisah.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-extrabold text-emerald-800">{{ $desaDenganAnggota }} sudah ada anggota</span>
                    <span class="rounded-full bg-amber-50 px-3 py-1 text-[10px] font-extrabold text-amber-800">{{ $desaMenunggu }} menunggu verifikasi</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-extrabold text-slate-600">{{ $desaTanpaAnggota }} belum ada anggota</span>
                </div>
            </div>

            <div class="mt-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <label for="cari-desa" class="relative block w-full lg:max-w-xs">
                    <span class="sr-only">Cari desa</span>
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0Z"/>
                    </svg>
                    <input id="cari-desa" type="search" placeholder="Cari nama desa..."
                           class="w-full rounded-xl border border-brand-border bg-white py-2.5 pl-9 pr-3 text-xs font-semibold text-slate-700 shadow-sm placeholder:text-slate-400 focus:border-brand focus:ring-brand">
                </label>

                <div class="flex flex-wrap gap-2" role="group" aria-label="Filter status desa">
                    <button type="button" data-filter-desa="semua"
                            class="filter-desa rounded-full bg-brand px-3.5 py-1.5 text-[11px] font-bold text-white shadow-sm transition">
                        Semua
                    </button>
                    <button type="button" data-filter-desa="ada"
                            class="filter-desa rounded-full border border-brand-border bg-white px-3.5 py-1.5 text-[11px] font-bold text-slate-600 transition hover:border-brand hover:text-brand">
                        Sudah ada anggota
                    </button>
                    <button type="button" data-filter-desa="pending"
                            class="filter-desa rounded-full border border-brand-border bg-white px-3.5 py-1.5 text-[11px] font-bold text-slate-600 transition hover:border-brand hover:text-brand">
                        Menunggu verifikasi
                    </button>
                    <button type="button" data-filter-desa="kosong"
                            class="filter-desa rounded-full border border-brand-border bg-white px-3.5 py-1.5 text-[11px] font-bold text-slate-600 transition hover:border-brand hover:text-brand">
                        Belum ada anggota
                    </button>
                </div>
            </div>

            @if ($desas->isNotEmpty())
                <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-100 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Desa</th>
                                <th scope="col" class="px-4 py-3 text-right text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Anggota resmi</th>
                                <th scope="col" class="px-4 py-3 text-right text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Pending</th>
                                <th scope="col" class="px-4 py-3 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Status desa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($desas as $desa)
                                @php
                                    $statusDesa = $desa->approved_count > 0
                                        ? ['key' => 'ada', 'label' => 'Sudah ada anggota', 'class' => 'bg-emerald-100 text-emerald-800', 'dot' => 'bg-emerald-500', 'border' => 'border-emerald-200']
                                        : ($desa->pending_count > 0
                                            ? ['key' => 'pending', 'label' => 'Menunggu verifikasi', 'class' => 'bg-amber-100 text-amber-800', 'dot' => 'bg-amber-500', 'border' => 'border-amber-200']
                                            : ['key' => 'kosong', 'label' => 'Belum ada anggota', 'class' => 'bg-slate-100 text-slate-600', 'dot' => 'bg-slate-400', 'border' => 'border-slate-200']);
                                @endphp
                                <tr class="desa-row transition hover:bg-emerald-50/40"
                                    data-nama="{{ mb_strtolower($desa->nama) }}"
                                    data-status="{{ $statusDesa['key'] }}">
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $statusDesa['class'] }} ring-1 {{ $statusDesa['border'] }}">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-4.35 7-11a7 7 0 1 0-14 0c0 6.65 7 11 7 11Z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 10.5h.01"/>
                                                </svg>
                                            </span>
                                            <div>
                                                <p class="text-xs font-extrabold text-slate-800">{{ $desa->nama }}</p>
                                                <p class="text-[10px] text-slate-400">Kecamatan Ajibarang</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        <span class="text-sm font-black tabular-nums {{ $desa->approved_count > 0 ? 'text-brand' : 'text-slate-400' }}">
                                            {{ number_format($desa->approved_count) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-right">
                                        @if ($desa->pending_count > 0)
                                            <span class="inline-flex min-w-[2rem] justify-center rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-extrabold text-amber-800">
                                                {{ number_format($desa->pending_count) }}
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-extrabold {{ $statusDesa['class'] }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $statusDesa['dot'] }}" aria-hidden="true"></span>
                                            {{ $statusDesa['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="desa-kosong-filter" class="mt-3 hidden rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center">
                    <p class="text-sm font-bold text-slate-700">Tidak ada desa yang sesuai.</p>
                    <p class="mt-1 text-xs text-slate-500">Ubah kata pencarian atau pilih filter “Semua”.</p>
                </div>

                <p class="mt-3 text-[10.5px] leading-5 text-slate-400">
                    Menampilkan <span id="jumlah-desa-tampil" class="font-bold text-slate-600">{{ $desas->count() }}</span> dari {{ $desas->count() }} desa.
                    @if ($anggotaTanpaDesa > 0)
                        <span class="mt-1 block font-semibold text-amber-700">
                            Catatan: {{ $anggotaTanpaDesa }} anggota resmi lama belum memiliki data desa, sehingga belum masuk hitungan per desa.
                            Data dapat dilengkapi melalui formulir anggota.
                        </span>
                    @else
                        Anggota lama yang belum memiliki data desa tidak dihitung per desa sampai datanya dilengkapi.
                    @endif
                </p>
            @else
                <p class="mt-4 rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                    Belum ada data desa.
                </p>
            @endif
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.bar-fill').forEach((bar, index) => {
                    bar.style.transitionDelay = `${index * 30}ms`;
                    requestAnimationFrame(() => {
                        bar.style.width = bar.dataset.width || '0%';
                    });
                });

                const inputCariDesa = document.getElementById('cari-desa');
                const tombolFilterDesa = document.querySelectorAll('.filter-desa');
                const barisDesa = document.querySelectorAll('.desa-row');
                const pesanDesaKosong = document.getElementById('desa-kosong-filter');
                const jumlahDesaTampil = document.getElementById('jumlah-desa-tampil');
                let filterDesaAktif = 'semua';

                const terapkanFilterDesa = () => {
                    const kataKunci = (inputCariDesa?.value || '').trim().toLowerCase();
                    let jumlahTampil = 0;

                    barisDesa.forEach((baris) => {
                        const cocokNama = (baris.dataset.nama || '').includes(kataKunci);
                        const cocokStatus = filterDesaAktif === 'semua' || baris.dataset.status === filterDesaAktif;
                        const cocok = cocokNama && cocokStatus;

                        baris.classList.toggle('hidden', !cocok);

                        if (cocok) {
                            jumlahTampil++;
                        }
                    });

                    if (jumlahDesaTampil) {
                        jumlahDesaTampil.textContent = String(jumlahTampil);
                    }

                    if (pesanDesaKosong) {
                        pesanDesaKosong.classList.toggle('hidden', jumlahTampil > 0 || barisDesa.length === 0);
                    }
                };

                const aktifkanFilterDesa = (tombolAktif) => {
                    tombolFilterDesa.forEach((tombol) => {
                        tombol.classList.remove('bg-brand', 'text-white', 'shadow-sm');
                        tombol.classList.add('border', 'border-brand-border', 'bg-white', 'text-slate-600');
                    });

                    tombolAktif.classList.remove('border', 'border-brand-border', 'bg-white', 'text-slate-600');
                    tombolAktif.classList.add('bg-brand', 'text-white', 'shadow-sm');
                };

                tombolFilterDesa.forEach((tombol) => {
                    tombol.addEventListener('click', () => {
                        filterDesaAktif = tombol.dataset.filterDesa || 'semua';
                        aktifkanFilterDesa(tombol);
                        terapkanFilterDesa();
                    });
                });

                inputCariDesa?.addEventListener('input', terapkanFilterDesa);

                document.querySelectorAll('.count-up').forEach((element) => {
                    const target = Number(element.dataset.count || 0);
                    const duration = 800;
                    const start = performance.now();

                    if (!Number.isFinite(target) || target <= 0) {
                        element.textContent = Number.isFinite(target) ? target.toLocaleString('id-ID') : '0';
                        return;
                    }

                    const tick = (now) => {
                        const progress = Math.min((now - start) / duration, 1);
                        const value = Math.round(target * (1 - Math.pow(1 - progress, 3)));
                        element.textContent = value.toLocaleString('id-ID');

                        if (progress < 1) {
                            requestAnimationFrame(tick);
                        }
                    };

                    requestAnimationFrame(tick);
                });
            });
        </script>
    @endpush
</x-admin-layout>

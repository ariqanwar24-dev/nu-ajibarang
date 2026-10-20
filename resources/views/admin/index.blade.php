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
                    'label' => 'Total Berita',
                    'nilai' => $jumlahBerita,
                    'catatan' => $jumlahBeritaTerbit.' terbit · '.$jumlahBeritaDraft.' draft',
                    'route' => route('admin.berita.index'),
                    'warna' => 'emerald',
                ],
                [
                    'label' => 'Total Pengurus',
                    'nilai' => $jumlahPengurus,
                    'catatan' => 'Data pada struktur organisasi',
                    'route' => route('pengurus.index'),
                    'warna' => 'teal',
                ],
                [
                    'label' => 'Total Anggota',
                    'nilai' => $jumlahAnggota,
                    'catatan' => $anggotaDisetujui.' sudah disetujui',
                    'route' => route('admin.anggota.index'),
                    'warna' => 'sky',
                ],
                [
                    'label' => 'Menunggu Verifikasi',
                    'nilai' => $anggotaMenunggu,
                    'catatan' => 'Perlu ditinjau admin',
                    'route' => route('admin.anggota.index'),
                    'warna' => 'amber',
                ],
                [
                    'label' => 'Total Desa',
                    'nilai' => $desas->count(),
                    'catatan' => 'Wilayah Kecamatan Ajibarang',
                    'route' => null,
                    'warna' => 'indigo',
                ],
                [
                    'label' => 'Kegiatan Desa',
                    'nilai' => $totalKegiatanDesa,
                    'catatan' => 'Agregat data desa (demo)',
                    'route' => null,
                    'warna' => 'rose',
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

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(320px,.85fr)]">
            <div class="space-y-6">
                {{-- BERITA TERBARU --}}
                <section class="dash-rise dash-delay-1 overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                        <div>
                            <h2 class="text-lg font-black tracking-tight text-slate-900">Berita Terbaru</h2>
                            <p class="mt-0.5 text-xs text-brand-muted">Lima publikasi terakhir dari basis data.</p>
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
                                            'bg-emerald-100 text-emerald-800' => $item->sudahTerbit(),
                                            'bg-slate-200 text-slate-700' => ! $item->sudahTerbit(),
                                        ])>
                                            {{ $item->sudahTerbit() ? 'Terbit' : 'Draft' }}
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
                            <h2 class="text-lg font-black tracking-tight text-slate-900">Sebaran Anggota per Desa</h2>
                            <p class="mt-0.5 text-xs text-brand-muted">Visualisasi data desa yang tersimpan pada tabel <span class="font-mono text-[10px]">desas</span>.</p>
                        </div>
                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[9.5px] font-extrabold uppercase tracking-wider text-amber-800">
                            Data demo
                        </span>
                    </div>

                    @if ($desas->isNotEmpty())
                        @php $maxAnggota = max(1, (int) $desas->max('jumlah_anggota')); @endphp
                        <div class="mt-5 grid gap-x-8 gap-y-3.5 sm:grid-cols-2">
                            @foreach ($desas as $desa)
                                <div>
                                    <div class="mb-1 flex items-center justify-between gap-2">
                                        <span class="truncate text-xs font-semibold text-slate-600">{{ $desa->nama }}</span>
                                        <span class="shrink-0 text-[11px] font-extrabold tabular-nums text-brand">{{ number_format($desa->jumlah_anggota) }}</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                        <div class="bar-fill h-full rounded-full bg-gradient-to-r from-brand to-teal-500"
                                             data-width="{{ round(($desa->jumlah_anggota / $maxAnggota) * 100, 2) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-4 text-[10.5px] leading-relaxed text-slate-400">
                            Angka pada grafik berasal dari kolom <span class="font-mono">jumlah_anggota</span> pada data desa demo,
                            bukan jumlah akun anggota terverifikasi.
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
                            {{ $jumlahAnggota }} total
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
                                        · {{ $anggota->user?->email ?? 'tanpa akun' }}
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
                            <span class="text-xs text-emerald-50/80">Desa terdata</span>
                            <strong class="text-lg">{{ $desas->count() }}</strong>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <span class="text-xs text-emerald-50/80">Ranting (data desa)</span>
                            <strong class="text-lg">{{ number_format($totalRantingDesa) }}</strong>
                        </div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-2.5">
                            <span class="text-xs text-emerald-50/80">Berita terbit</span>
                            <strong class="text-lg">{{ $jumlahBeritaTerbit }}</strong>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-emerald-50/80">Menunggu verifikasi</span>
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

        {{-- DAFTAR DESA --}}
        <section class="dash-rise dash-delay-3 rounded-3xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-black tracking-tight text-slate-900">Desa Kecamatan Ajibarang</h2>
                    <p class="mt-0.5 text-xs text-brand-muted">Daftar desa dari tabel <span class="font-mono text-[10px]">desas</span>; metrik kegiatan dan ranting masih berupa data demo.</p>
                </div>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-800">{{ $desas->count() }} desa</span>
            </div>

            @if ($desas->isNotEmpty())
                <div class="mt-4 grid gap-2.5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    @foreach ($desas as $index => $desa)
                        <div class="stat-tile flex items-start gap-2.5 rounded-2xl border border-slate-100 bg-slate-50/70 p-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand text-[10px] font-black text-white">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-xs font-extrabold text-slate-800">{{ $desa->nama }}</p>
                                <p class="mt-0.5 text-[10px] leading-relaxed text-slate-500">
                                    {{ number_format($desa->jumlah_anggota) }} anggota ·
                                    {{ number_format($desa->jumlah_kegiatan) }} kegiatan ·
                                    {{ number_format($desa->jumlah_ranting) }} ranting
                                    <span class="text-amber-700">(demo)</span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
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

@php
    $status = $anggota->status_verifikasi;

    $statusConfig = [
        'pending' => [
            'label' => 'Menunggu Verifikasi',
            'short' => 'Sedang diperiksa admin',
            'description' => 'Data Anda sudah diterima. Admin sedang memeriksa kelengkapan dan keaslian data sebelum kartu anggota dapat digunakan.',
            'icon' => 'M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            'wrapper' => 'border-amber-200 bg-amber-50',
            'iconBox' => 'bg-amber-100 text-amber-700',
            'title' => 'text-amber-900',
            'text' => 'text-amber-900/75',
            'badge' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'step' => 2,
        ],
        'approved' => [
            'label' => 'Disetujui',
            'short' => 'Kartu anggota aktif',
            'description' => 'Pendaftaran Anda telah disetujui. Kartu anggota sudah dapat dilihat dan dicetak.',
            'icon' => 'm5 12 4.5 4.5L19 7',
            'wrapper' => 'border-emerald-200 bg-emerald-50',
            'iconBox' => 'bg-emerald-100 text-emerald-700',
            'title' => 'text-emerald-900',
            'text' => 'text-emerald-900/75',
            'badge' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'step' => 3,
        ],
        'rejected' => [
            'label' => 'Perlu Perbaikan',
            'short' => 'Belum disetujui',
            'description' => 'Pengajuan Anda belum disetujui. Perbaiki data sesuai catatan admin, lalu ajukan verifikasi kembali.',
            'icon' => 'M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z',
            'wrapper' => 'border-red-200 bg-red-50',
            'iconBox' => 'bg-red-100 text-red-700',
            'title' => 'text-red-900',
            'text' => 'text-red-900/75',
            'badge' => 'bg-red-100 text-red-700 ring-red-200',
            'step' => 2,
        ],
    ];

    $statusCard = $statusConfig[$status] ?? $statusConfig['pending'];
    $fotoTersimpan = $anggota->foto;
    $namaDesa = $anggota->desa?->nama ?? 'Belum diisi';
    $rt = filled($anggota->rt) ? $anggota->rt : '—';
    $rw = filled($anggota->rw) ? $anggota->rw : '—';
@endphp

<x-member-layout
    title="Status Kartu Anggota | MWC NU Ajibarang"
    eyebrow="Status Layanan"
    heading="Status Pendaftaran Kartu Anggota"
    description="Pantau proses verifikasi data anggota Anda. Status hanya dapat diubah oleh admin melalui panel verifikasi."
>
    <div class="mx-auto max-w-5xl px-4 pt-6 sm:px-6 lg:px-8">
        <nav class="flex flex-wrap items-center gap-1.5 text-[11px] font-semibold text-brand-muted" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition hover:text-brand">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('kartu.index') }}" class="transition hover:text-brand">Kartu Anggota</a>
            <span aria-hidden="true">/</span>
            <span class="text-brand">Status</span>
        </nav>

        <div class="mt-5 grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_330px]">
            <div class="space-y-6">
                <section class="overflow-hidden rounded-3xl border {{ $statusCard['wrapper'] }} shadow-sm">
                    <div class="p-5 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex min-w-0 items-start gap-3.5">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $statusCard['iconBox'] }}" aria-hidden="true">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $statusCard['icon'] }}"/>
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] {{ $statusCard['text'] }}">Status verifikasi</p>
                                    <h2 class="mt-0.5 font-serif text-2xl font-medium {{ $statusCard['title'] }}">{{ $statusCard['label'] }}</h2>
                                    <p class="mt-1 text-sm leading-6 {{ $statusCard['text'] }}">{{ $statusCard['description'] }}</p>
                                </div>
                            </div>
                            <span class="shrink-0 self-start rounded-full px-3 py-1 text-[10px] font-extrabold uppercase tracking-wide ring-1 {{ $statusCard['badge'] }}">
                                {{ $statusCard['short'] }}
                            </span>
                        </div>

                        @if ($status === 'rejected')
                            <div class="mt-5 rounded-2xl border border-red-200/80 bg-white/70 p-4">
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-red-800">Catatan dari admin</p>
                                <p class="mt-1.5 whitespace-pre-line text-sm leading-6 text-red-900/80">
                                    {{ $anggota->catatan_verifikasi ?: 'Belum ada catatan dari admin.' }}
                                </p>
                            </div>
                        @endif

                        <div class="mt-5 flex flex-wrap items-center gap-2.5">
                            @if ($status === 'approved')
                                <a href="{{ route('kartu.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:bg-brand-dark">
                                    Lihat Kartu Anggota
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            @endif

                            <a href="{{ route('anggota.create') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-brand-border bg-white px-4 py-2.5 text-xs font-bold text-brand transition hover:border-brand hover:bg-brand-surface">
                                {{ $status === 'rejected' ? 'Perbaiki Data' : 'Perbarui Data' }}
                            </a>

                            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2.5 text-xs font-bold text-brand-muted transition hover:text-brand">
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>

                    <div class="border-t border-black/5 px-5 py-3.5 text-[11px] leading-5 sm:px-6 {{ $statusCard['text'] }}">
                        Status verifikasi hanya dapat diubah oleh admin. Anda tidak dapat mengubah status atau data anggota milik pengguna lain.
                    </div>
                </section>

                <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="flex items-start justify-between gap-4 border-b border-brand-border/70 px-5 py-4 sm:px-6">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-accent">Data terdaftar</p>
                            <h2 class="mt-1 text-base font-black tracking-tight text-brand-deeper">Ringkasan data anggota</h2>
                        </div>
                        @if ($anggota->nomor_anggota)
                            <span class="shrink-0 rounded-full bg-brand/10 px-3 py-1 text-[10px] font-extrabold text-brand">
                                {{ $anggota->nomor_anggota }}
                            </span>
                        @endif
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-[150px_minmax(0,1fr)] sm:p-6">
                        <div class="mx-auto w-full max-w-[150px] sm:mx-0">
                            <div class="aspect-[4/5] overflow-hidden rounded-2xl border border-brand-border bg-[#F8F7F2]">
                                @if ($fotoTersimpan)
                                    <img src="{{ asset('storage/'.$fotoTersimpan) }}" alt="Foto {{ $anggota->nama_anggota }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full flex-col items-center justify-center px-3 text-center">
                                        <svg class="h-7 w-7 text-brand/35" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/>
                                        </svg>
                                        <p class="mt-2 text-[10px] font-bold text-brand-muted">Foto belum tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <dl class="space-y-4 text-sm">
                            <div class="grid gap-1 border-b border-brand-border/60 pb-3 sm:grid-cols-[130px_minmax(0,1fr)] sm:gap-3">
                                <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Nama lengkap</dt>
                                <dd class="font-semibold text-neutral-900">{{ $anggota->nama_anggota }}</dd>
                            </div>
                            <div class="grid gap-1 border-b border-brand-border/60 pb-3 sm:grid-cols-[130px_minmax(0,1fr)] sm:gap-3">
                                <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Tanggal lahir</dt>
                                <dd class="font-semibold text-neutral-900">{{ $anggota->tanggal_lahir?->format('d-m-Y') ?? 'Belum diisi' }}</dd>
                            </div>
                            <div class="grid gap-1 border-b border-brand-border/60 pb-3 sm:grid-cols-[130px_minmax(0,1fr)] sm:gap-3">
                                <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Jenis kelamin</dt>
                                <dd class="font-semibold text-neutral-900">{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                            </div>
                            <div class="grid gap-1 border-b border-brand-border/60 pb-3 sm:grid-cols-[130px_minmax(0,1fr)] sm:gap-3">
                                <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Desa</dt>
                                <dd class="font-semibold text-neutral-900">{{ $namaDesa }}</dd>
                            </div>
                            <div class="grid gap-1 border-b border-brand-border/60 pb-3 sm:grid-cols-[130px_minmax(0,1fr)] sm:gap-3">
                                <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">RT / RW</dt>
                                <dd class="font-semibold text-neutral-900">{{ $rt }} / {{ $rw }}</dd>
                            </div>
                            <div class="grid gap-1 sm:grid-cols-[130px_minmax(0,1fr)] sm:gap-3">
                                <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Nomor HP</dt>
                                <dd class="font-semibold text-neutral-900">{{ $anggota->no_hp }}</dd>
                            </div>
                        </dl>
                    </div>
                </section>
            </div>

            <aside class="space-y-5">
                <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="border-b border-brand-border/70 px-5 py-4">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-accent">Progres</p>
                        <h2 class="mt-1 text-base font-black tracking-tight text-brand-deeper">Tahapan pendaftaran</h2>
                    </div>

                    <ol class="space-y-4 p-5">
                        @php
                            $langkah = [
                                1 => ['judul' => 'Data dikirim', 'teks' => 'Formulir pendaftaran berhasil diterima.'],
                                2 => ['judul' => 'Verifikasi admin', 'teks' => 'Admin memeriksa kelengkapan data.'],
                                3 => ['judul' => 'Kartu aktif', 'teks' => 'Kartu dapat dilihat dan dicetak.'],
                            ];
                        @endphp

                        @foreach ($langkah as $nomor => $item)
                            @php
                                $selesai = $nomor < $statusCard['step'];
                                $aktif = $nomor === $statusCard['step'];
                                $gagal = $aktif && $status === 'rejected';
                            @endphp
                            <li class="flex gap-3">
                                <span @class([
                                    'flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[11px] font-extrabold',
                                    'bg-emerald-100 text-emerald-700' => $selesai,
                                    'bg-red-100 text-red-700' => $gagal,
                                    'bg-brand text-white' => $aktif && ! $gagal,
                                    'bg-slate-100 text-slate-500' => ! $selesai && ! $aktif,
                                ])>
                                    @if ($selesai)
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m5 12 4.5 4.5L19 7"/>
                                        </svg>
                                    @else
                                        {{ $nomor }}
                                    @endif
                                </span>
                                <span>
                                    <span @class([
                                        'block text-xs font-extrabold',
                                        'text-brand' => $aktif,
                                        'text-slate-700' => ! $aktif,
                                    ])>{{ $item['judul'] }}</span>
                                    <span class="mt-0.5 block text-[11px] leading-5 text-brand-muted">{{ $item['teks'] }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <section class="rounded-3xl border border-brand-border bg-[#FBFAF6] p-5 shadow-sm">
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-accent">Butuh bantuan?</p>
                    <p class="mt-2 text-xs leading-6 text-brand-muted">
                        Jika status tidak berubah dalam waktu yang lama atau ada data yang perlu dikoreksi, hubungi admin MWC NU Kecamatan Ajibarang.
                    </p>
                    <a href="{{ route('home') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-extrabold text-brand transition hover:text-brand-dark">
                        Kembali ke Beranda
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </section>
            </aside>
        </div>
    </div>
</x-member-layout>

@php
    $inisial = collect(preg_split('/\s+/u', trim((string) $anggota->nama_anggota)) ?: [])
        ->filter()
        ->take(2)
        ->map(fn (string $kata): string => mb_strtoupper(mb_substr($kata, 0, 1)))
        ->implode('');

    $fotoUrl = $anggota->foto ? asset('storage/'.$anggota->foto) : null;
    $tanggalLahir = $anggota->tanggal_lahir?->locale('id')->translatedFormat('d F Y') ?? 'Belum diisi';
    $namaDesa = $anggota->desa?->nama ?? 'Belum diisi';
    $rt = filled($anggota->rt) ? $anggota->rt : '—';
    $rw = filled($anggota->rw) ? $anggota->rw : '—';
    $tahunKartu = $anggota->created_at?->format('Y') ?? date('Y');
@endphp

<x-member-layout
    title="Kartu Anggota | MWC NU Ajibarang"
    eyebrow="Kartu Anggota Resmi"
    heading="Kartu Anggota Digital"
    description="Kartu ini diterbitkan hanya untuk anggota yang telah disetujui. Gunakan tombol cetak untuk menyimpan versi PDF atau mencetak kartu fisik."
>
    @push('styles')
        <style>
            .card-stage {
                position: relative;
                overflow: hidden;
                border-radius: 22px;
                padding: clamp(14px, 3.4vw, 30px);
                background:
                    radial-gradient(circle at 12% 0%, rgba(23, 74, 53, 0.12), transparent 38%),
                    linear-gradient(135deg, #f2f6f1 0%, #f8f7f2 55%, #eef3ee 100%);
            }

            .card-stage::before {
                content: "";
                position: absolute;
                inset: 0;
                pointer-events: none;
                opacity: 0.45;
                background-image: radial-gradient(circle at 1px 1px, rgba(23, 74, 53, 0.16) 1px, transparent 0);
                background-size: 18px 18px;
            }

            .member-card {
                position: relative;
                z-index: 1;
                width: 100%;
                max-width: 760px;
                margin: 0 auto;
                overflow: hidden;
                border: 1px solid #dbe4dc;
                border-radius: 22px;
                background: #ffffff;
                color: #17231d;
                box-shadow: 0 24px 55px -28px rgba(8, 37, 26, 0.45);
                font-family: "Plus Jakarta Sans", sans-serif;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .card-header {
                position: relative;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 18px;
                padding: 21px 25px 20px;
                overflow: hidden;
                background: linear-gradient(135deg, #0e3022 0%, #174a35 56%, #1f5b42 100%);
                color: #ffffff;
            }

            .card-header::before {
                content: "";
                position: absolute;
                top: -42px;
                right: 82px;
                width: 150px;
                height: 150px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 999px;
            }

            .card-header::after {
                content: "";
                position: absolute;
                right: 0;
                bottom: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #b69a5a 0%, #d9c28d 36%, rgba(217, 194, 141, 0) 100%);
            }

            .card-brand {
                position: relative;
                z-index: 1;
                display: flex;
                min-width: 0;
                align-items: center;
                gap: 14px;
            }

            .card-logo {
                display: flex;
                width: 54px;
                height: 54px;
                flex: 0 0 auto;
                align-items: center;
                justify-content: center;
                padding: 5px;
                border: 2px solid rgba(217, 194, 141, 0.65);
                border-radius: 999px;
                background: #ffffff;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.16);
            }

            .card-logo img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .card-org-kicker {
                margin: 0;
                color: #d9c28d;
                font-size: 8px;
                font-weight: 800;
                letter-spacing: 0.19em;
                line-height: 1.3;
            }

            .card-org-name {
                margin: 3px 0 0;
                color: #ffffff;
                font-family: "Newsreader", serif;
                font-size: 23px;
                font-weight: 500;
                letter-spacing: -0.01em;
                line-height: 1.02;
            }

            .card-org-loc {
                margin: 5px 0 0;
                color: rgba(236, 253, 245, 0.7);
                font-size: 9px;
                font-weight: 500;
                letter-spacing: 0.01em;
                line-height: 1.35;
            }

            .card-loc-short {
                display: none;
            }

            .card-type {
                position: relative;
                z-index: 1;
                flex: 0 0 auto;
                padding-top: 2px;
                text-align: right;
            }

            .card-type-label {
                display: block;
                color: #d9c28d;
                font-size: 8px;
                font-weight: 800;
                letter-spacing: 0.17em;
                line-height: 1.3;
            }

            .card-type-year {
                display: block;
                margin-top: 2px;
                color: rgba(255, 255, 255, 0.88);
                font-size: 21px;
                font-weight: 700;
                font-variant-numeric: tabular-nums;
                letter-spacing: -0.02em;
                line-height: 1;
            }

            .card-body {
                display: grid;
                grid-template-columns: 168px minmax(0, 1fr);
                gap: 24px;
                padding: 24px 25px 22px;
                background: #ffffff;
            }

            .card-photo {
                position: relative;
                width: 100%;
                aspect-ratio: 4 / 5;
                overflow: hidden;
                border: 1px solid #d9e2da;
                border-radius: 15px;
                background: #f0f5f1;
            }

            .card-photo img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .card-photo-placeholder {
                display: flex;
                height: 100%;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 9px;
                padding: 10px;
                background:
                    radial-gradient(circle at 30% 20%, rgba(23, 74, 53, 0.09), transparent 50%),
                    #f0f5f1;
                text-align: center;
            }

            .card-photo-initials {
                display: flex;
                width: 64px;
                height: 64px;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                background: #174a35;
                color: #ffffff;
                font-family: "Newsreader", serif;
                font-size: 26px;
                font-weight: 500;
                letter-spacing: 0.03em;
            }

            .card-photo-caption {
                color: #65736a;
                font-size: 8px;
                font-weight: 700;
                letter-spacing: 0.12em;
                line-height: 1.4;
                text-transform: uppercase;
            }

            .card-identity {
                min-width: 0;
                padding-top: 2px;
            }

            .card-role {
                margin: 0;
                color: #b08b45;
                font-size: 8px;
                font-weight: 800;
                letter-spacing: 0.19em;
                line-height: 1.3;
                text-transform: uppercase;
            }

            .card-name {
                margin: 5px 0 0;
                overflow-wrap: anywhere;
                color: #0e3022;
                font-family: "Newsreader", serif;
                font-size: clamp(23px, 3.1vw, 34px);
                font-weight: 500;
                letter-spacing: -0.025em;
                line-height: 1.04;
            }

            .card-number {
                display: flex;
                flex-wrap: wrap;
                align-items: baseline;
                gap: 4px 10px;
                margin-top: 11px;
                padding-bottom: 12px;
                border-bottom: 1px solid #e5eae5;
            }

            .card-number span {
                color: #7a867e;
                font-size: 8px;
                font-weight: 800;
                letter-spacing: 0.13em;
                line-height: 1.3;
                text-transform: uppercase;
            }

            .card-number strong {
                overflow-wrap: anywhere;
                color: #174a35;
                font-size: 13px;
                font-weight: 800;
                font-variant-numeric: tabular-nums;
                letter-spacing: 0.07em;
                line-height: 1.2;
            }

            .card-meta {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px 20px;
                margin: 14px 0 0;
            }

            .card-meta dt {
                margin: 0;
                color: #7a867e;
                font-size: 8px;
                font-weight: 800;
                letter-spacing: 0.11em;
                line-height: 1.3;
                text-transform: uppercase;
            }

            .card-meta dd {
                margin: 3px 0 0;
                overflow-wrap: anywhere;
                color: #25352c;
                font-size: 12px;
                font-weight: 600;
                line-height: 1.4;
            }

            .status-value {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                color: #176b3a;
            }

            .status-dot {
                width: 6px;
                height: 6px;
                flex: 0 0 auto;
                border-radius: 999px;
                background: #22c55e;
                box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.13);
            }

            .card-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 18px;
                padding: 11px 25px;
                border-top: 1px solid #e5eae5;
                background: #f8faf7;
                color: #65736a;
                font-size: 8px;
                line-height: 1.45;
            }

            .card-footer p {
                margin: 0;
            }

            .card-footer strong {
                color: #174a35;
                font-weight: 800;
            }

            .card-footer-note {
                flex: 0 0 auto;
                text-align: right;
            }

            @media screen and (max-width: 560px) {
                .card-header {
                    gap: 10px;
                    padding: 16px;
                }

                .card-logo {
                    width: 44px;
                    height: 44px;
                    padding: 4px;
                }

                .card-brand {
                    gap: 10px;
                }

                .card-org-name {
                    font-size: 18px;
                }

                .card-org-loc {
                    font-size: 8px;
                }

                .card-type-label {
                    font-size: 7px;
                }

                .card-type-year {
                    font-size: 17px;
                }

                .card-body {
                    grid-template-columns: 106px minmax(0, 1fr);
                    gap: 14px;
                    padding: 16px;
                }

                .card-name {
                    font-size: 21px;
                }

                .card-meta {
                    grid-template-columns: 1fr;
                    gap: 8px;
                    margin-top: 11px;
                }

                .card-meta dd {
                    margin-top: 1px;
                    font-size: 11px;
                }

                .card-footer {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 2px;
                    padding: 10px 16px;
                    font-size: 7.5px;
                }

                .card-footer-note {
                    text-align: left;
                }
            }

            @media screen and (max-width: 380px) {
                .card-body {
                    grid-template-columns: 1fr;
                }

                .card-photo {
                    width: 118px;
                    margin: 0 auto;
                }

                .card-identity {
                    text-align: center;
                }

                .card-number {
                    justify-content: center;
                }

                .card-meta {
                    text-align: left;
                }
            }

            @media print {
                @page {
                    size: 85.6mm 54mm;
                    margin: 0;
                }

                html,
                body {
                    width: 85.6mm;
                    height: 54mm;
                    margin: 0 !important;
                    padding: 0 !important;
                    background: #ffffff !important;
                }

                body > header,
                body > footer,
                main > section:first-of-type,
                .no-print {
                    display: none !important;
                }

                main {
                    margin: 0 !important;
                    padding: 0 !important;
                }

                .card-page,
                .card-layout,
                .card-preview-panel,
                .card-stage {
                    display: block !important;
                    width: auto !important;
                    max-width: none !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    border: 0 !important;
                    border-radius: 0 !important;
                    background: #ffffff !important;
                    box-shadow: none !important;
                }

                .card-stage::before {
                    display: none !important;
                }

                .member-card {
                    width: 85.6mm !important;
                    height: 54mm !important;
                    min-height: 0 !important;
                    max-width: none !important;
                    margin: 0 !important;
                    border: 0.25mm solid #174a35 !important;
                    border-radius: 3mm !important;
                    box-shadow: none !important;
                }

                .card-header {
                    gap: 2mm !important;
                    padding: 2.2mm 3.5mm !important;
                }

                .card-logo {
                    width: 8mm !important;
                    height: 8mm !important;
                    padding: 0.7mm !important;
                    border-width: 0.3mm !important;
                }

                .card-brand {
                    gap: 2.2mm !important;
                }

                .card-org-kicker {
                    font-size: 4.6pt !important;
                    letter-spacing: 0.14em !important;
                }

                .card-org-name {
                    margin-top: 0.5mm !important;
                    font-size: 10pt !important;
                    line-height: 1 !important;
                }

                .card-org-loc {
                    margin-top: 0.6mm !important;
                    font-size: 5pt !important;
                }

                .card-loc-full {
                    display: none !important;
                }

                .card-loc-short {
                    display: inline !important;
                }

                .card-type-label {
                    font-size: 5pt !important;
                }

                .card-type-year {
                    font-size: 8pt !important;
                }

                .card-body {
                    grid-template-columns: 18mm minmax(0, 1fr) !important;
                    gap: 3mm !important;
                    height: 33.6mm !important;
                    padding: 2.8mm 3.5mm !important;
                }

                .card-photo {
                    width: 18mm !important;
                    height: 22mm !important;
                    aspect-ratio: auto !important;
                    border-width: 0.2mm !important;
                    border-radius: 2mm !important;
                }

                .card-photo-initials {
                    width: 7mm !important;
                    height: 7mm !important;
                    font-size: 9pt !important;
                }

                .card-photo-caption {
                    font-size: 4.3pt !important;
                }

                .card-role {
                    font-size: 5pt !important;
                }

                .card-name {
                    margin-top: 0.6mm !important;
                    font-size: 11pt !important;
                    line-height: 1.05 !important;
                }

                .card-number {
                    gap: 1mm 2mm !important;
                    margin-top: 1.2mm !important;
                    padding-bottom: 1mm !important;
                }

                .card-number span {
                    font-size: 4.8pt !important;
                }

                .card-number strong {
                    font-size: 7pt !important;
                }

                .card-meta {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                    gap: 1.2mm 3mm !important;
                    margin-top: 1.5mm !important;
                }

                .card-meta dt {
                    font-size: 4.4pt !important;
                }

                .card-meta dd {
                    margin-top: 0.4mm !important;
                    font-size: 6pt !important;
                    line-height: 1.15 !important;
                }

                .status-dot {
                    width: 1.3mm !important;
                    height: 1.3mm !important;
                    box-shadow: none !important;
                }

                .card-footer {
                    gap: 3mm !important;
                    padding: 1.4mm 3.5mm !important;
                    font-size: 4.2pt !important;
                    line-height: 1.2 !important;
                }
            }
        </style>
    @endpush

    <div class="card-page mx-auto max-w-5xl px-4 pt-6 sm:px-6 lg:px-8">
        <nav class="no-print flex flex-wrap items-center gap-1.5 text-[11px] font-semibold text-brand-muted" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition hover:text-brand">Beranda</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('anggota.create') }}" class="transition hover:text-brand">Kartu Anggota</a>
            <span aria-hidden="true">/</span>
            <span class="text-brand">Kartu Resmi</span>
        </nav>

        <div class="card-layout mt-5 grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_310px]">
            <section class="card-preview-panel overflow-hidden rounded-3xl border border-brand-border bg-white p-4 shadow-sm sm:p-6">
                <div class="no-print mb-4 flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-brand-accent">Pratinjau kartu</p>
                        <h2 class="mt-1 font-serif text-2xl font-medium tracking-tight text-brand-deeper">Kartu Anggota Resmi</h2>
                        <p class="mt-1 text-xs leading-5 text-brand-muted">Ukuran layar menyesuaikan perangkat. Versi cetak menggunakan ukuran kartu standar 85,6 × 54 mm.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wide text-emerald-800 ring-1 ring-emerald-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" aria-hidden="true"></span>
                        Kartu Aktif
                    </span>
                </div>

                <div class="card-stage">
                    <article class="member-card" id="kartu-anggota" aria-label="Kartu anggota {{ $anggota->nama_anggota }}">
                        <header class="card-header">
                            <div class="card-brand">
                                <div class="card-logo">
                                    <img src="{{ asset('images/logo-nu.jpg') }}" alt="Logo Nahdlatul Ulama">
                                </div>
                                <div class="min-w-0">
                                    <p class="card-org-kicker">Majelis Wakil Cabang</p>
                                    <p class="card-org-name">Nahdlatul Ulama</p>
                                    <p class="card-org-loc">
                                        <span class="card-loc-full">Kecamatan Ajibarang · Kabupaten Banyumas, Jawa Tengah</span>
                                        <span class="card-loc-short">Kec. Ajibarang · Banyumas</span>
                                    </p>
                                </div>
                            </div>

                            <div class="card-type">
                                <span class="card-type-label">Kartu Anggota</span>
                                <span class="card-type-year">{{ $tahunKartu }}</span>
                            </div>
                        </header>

                        <div class="card-body">
                            <div class="card-photo">
                                @if ($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="Foto {{ $anggota->nama_anggota }}">
                                @else
                                    <div class="card-photo-placeholder">
                                        <span class="card-photo-initials">{{ $inisial ?: 'NU' }}</span>
                                        <span class="card-photo-caption">Foto belum tersedia</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-identity">
                                <p class="card-role">Anggota Resmi</p>
                                <h2 class="card-name">{{ $anggota->nama_anggota }}</h2>

                                <p class="card-number">
                                    <span>Nomor Anggota</span>
                                    <strong>{{ $nomorAnggota }}</strong>
                                </p>

                                <dl class="card-meta">
                                    <div>
                                        <dt>Desa</dt>
                                        <dd>{{ $namaDesa }}</dd>
                                    </div>
                                    <div>
                                        <dt>RT / RW</dt>
                                        <dd>{{ $rt }} / {{ $rw }}</dd>
                                    </div>
                                    <div>
                                        <dt>Tanggal Lahir</dt>
                                        <dd>{{ $tanggalLahir }}</dd>
                                    </div>
                                    <div>
                                        <dt>Nomor HP</dt>
                                        <dd>{{ $anggota->no_hp }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <footer class="card-footer">
                            <p><strong>MWC NU Kecamatan Ajibarang</strong><br>Diterbitkan resmi untuk anggota terverifikasi.</p>
                            <p class="card-footer-note">Berlaku selama anggota terdaftar<br>Kartu ini sah selama data anggota terverifikasi.</p>
                        </footer>
                    </article>
                </div>

                <div class="no-print mt-4 flex flex-col gap-2.5 border-t border-brand-border/70 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-[11px] leading-5 text-brand-muted">
                        Gunakan tombol cetak, lalu pilih <strong class="text-brand">Save as PDF</strong> pada dialog browser untuk menyimpan kartu digital.
                    </p>
                    <button type="button"
                            onclick="window.print()"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3 text-xs font-extrabold text-white shadow-sm transition hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 8V4h10v4M7 17H5a1 1 0 0 1-1-1v-5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-2M7 14h10v6H7v-6Z"/>
                        </svg>
                        Cetak / Simpan PDF
                    </button>
                </div>
            </section>

            <aside class="no-print space-y-5">
                <section class="overflow-hidden rounded-3xl border border-emerald-200 bg-emerald-50/70 shadow-sm">
                    <div class="border-b border-emerald-200/70 px-5 py-4">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-emerald-700">Status keanggotaan</p>
                        <h2 class="mt-1 font-serif text-2xl font-medium text-emerald-950">Disetujui</h2>
                    </div>
                    <div class="space-y-3 p-5">
                        <p class="text-xs leading-5 text-emerald-900/75">Data anggota sudah diverifikasi. Kartu resmi aktif dan dapat dicetak.</p>
                        <dl class="space-y-2 border-t border-emerald-200/70 pt-3 text-xs">
                            <div class="flex items-start justify-between gap-3">
                                <dt class="font-semibold text-emerald-900/65">Nomor anggota</dt>
                                <dd class="text-right font-extrabold text-emerald-950">{{ $nomorAnggota }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <dt class="font-semibold text-emerald-900/65">Terdaftar sejak</dt>
                                <dd class="text-right font-bold text-emerald-950">{{ $anggota->created_at?->format('d-m-Y') ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </section>

                <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="border-b border-brand-border/70 px-5 py-4">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-accent">Tindakan</p>
                        <h2 class="mt-1 text-base font-black tracking-tight text-brand-deeper">Kelola kartu Anda</h2>
                    </div>
                    <div class="space-y-2.5 p-5">
                        <button type="button"
                                onclick="window.print()"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand px-4 py-3 text-xs font-extrabold text-white shadow-sm transition hover:bg-brand-dark">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 8V4h10v4M7 17H5a1 1 0 0 1-1-1v-5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-2M7 14h10v6H7v-6Z"/>
                            </svg>
                            Cetak / Simpan PDF
                        </button>

                        <a href="{{ route('anggota.create') }}"
                           class="block rounded-xl border border-brand-border bg-white px-4 py-2.5 text-center text-xs font-bold text-brand transition hover:border-brand hover:bg-brand-surface">
                            Perbarui Data Anggota
                        </a>

                        <p class="rounded-xl bg-amber-50 px-3 py-2.5 text-[10.5px] leading-5 text-amber-800">
                            Menyimpan perubahan data akan mengajukan verifikasi ulang. Kartu aktif sementara tidak dapat digunakan sampai disetujui kembali.
                        </p>

                        <a href="{{ route('home') }}" class="block pt-1 text-center text-[11px] font-bold text-brand-muted transition hover:text-brand">
                            Kembali ke Beranda
                        </a>
                    </div>
                </section>

                <section class="rounded-3xl border border-brand-border bg-[#FBFAF6] p-5 shadow-sm">
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-accent">Privasi data</p>
                    <p class="mt-2 text-[11px] leading-5 text-brand-muted">
                        Kartu tidak menampilkan NIK, email, alamat jalan lengkap, atau data akun. Informasi pribadi tetap tersimpan di sistem dan hanya dapat diakses oleh admin yang berwenang.
                    </p>
                </section>
            </aside>
        </div>
    </div>
</x-member-layout>

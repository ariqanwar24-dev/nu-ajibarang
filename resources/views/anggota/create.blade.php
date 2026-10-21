@php
    $statusSaatIni = $anggota?->status_verifikasi;

    $statusCards = [
        'pending' => [
            'label' => 'Menunggu Verifikasi',
            'description' => 'Data Anda sudah dikirim dan sedang diperiksa oleh admin.',
            'icon' => 'M12 8v4l3 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            'wrapper' => 'border-amber-200 bg-amber-50',
            'iconBox' => 'bg-amber-100 text-amber-700',
            'title' => 'text-amber-900',
            'text' => 'text-amber-900/75',
            'badge' => 'bg-amber-100 text-amber-800 ring-amber-200',
        ],
        'approved' => [
            'label' => 'Disetujui',
            'description' => 'Data anggota sudah disetujui dan kartu anggota dapat digunakan.',
            'icon' => 'm5 12 4.5 4.5L19 7',
            'wrapper' => 'border-emerald-200 bg-emerald-50',
            'iconBox' => 'bg-emerald-100 text-emerald-700',
            'title' => 'text-emerald-900',
            'text' => 'text-emerald-900/75',
            'badge' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        ],
        'rejected' => [
            'label' => 'Perlu Perbaikan',
            'description' => 'Pengajuan belum disetujui. Perbaiki data sesuai catatan admin.',
            'icon' => 'M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z',
            'wrapper' => 'border-red-200 bg-red-50',
            'iconBox' => 'bg-red-100 text-red-700',
            'title' => 'text-red-900',
            'text' => 'text-red-900/75',
            'badge' => 'bg-red-100 text-red-700 ring-red-200',
        ],
    ];

    $statusCard = $anggota
        ? ($statusCards[$statusSaatIni] ?? $statusCards['pending'])
        : null;

    $stepAktif = ! $anggota
        ? 1
        : ($statusSaatIni === 'approved' ? 3 : 2);

    $kelasInput = 'block w-full rounded-xl border bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm outline-none transition placeholder:text-neutral-400 focus:border-brand focus:ring-4 focus:ring-brand/10';
    $kelasNormal = 'border-brand-border';
    $kelasError = 'border-red-300 focus:border-red-500 focus:ring-red-100';
    $fotoTersimpan = $anggota?->foto;
@endphp

<x-member-layout
    title="Pendaftaran Kartu Anggota | MWC NU Ajibarang"
    eyebrow="Layanan Anggota"
    heading="Pendaftaran Kartu Anggota"
    description="Lengkapi data diri Anda untuk mengajukan kartu anggota MWC NU Kecamatan Ajibarang. Data akan diverifikasi oleh admin sebelum kartu dapat digunakan."
>
    <div class="mx-auto max-w-5xl px-4 pt-6 sm:px-6 lg:px-8">
        <nav class="flex flex-wrap items-center gap-1.5 text-[11px] font-semibold text-brand-muted" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="transition hover:text-brand">Beranda</a>
            <span aria-hidden="true">/</span>
            <span>Kartu Anggota</span>
            <span aria-hidden="true">/</span>
            <span class="text-brand">{{ $anggota ? 'Perbarui Data' : 'Pendaftaran' }}</span>
        </nav>

        @if (session('success'))
            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-semibold leading-6 text-emerald-900" role="status">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700" aria-hidden="true">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m5 12 4.5 4.5L19 7"/>
                    </svg>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3.5 text-sm font-semibold leading-6 text-sky-900" role="status">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-100 text-sky-700" aria-hidden="true">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8h.01M11 12h1v5h1"/>
                    </svg>
                </span>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-900" role="alert">
                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700" aria-hidden="true">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01"/>
                    </svg>
                </span>
                <div>
                    <p class="font-extrabold">Ada data yang perlu diperiksa</p>
                    <p class="mt-0.5 text-xs leading-5 text-red-800/80">Lihat pesan di bawah kolom yang ditandai, lalu perbaiki sebelum mengirim ulang.</p>
                </div>
            </div>
        @endif

        @if ($statusCard)
            <section class="mt-5 overflow-hidden rounded-3xl border {{ $statusCard['wrapper'] }} shadow-sm" aria-label="Status pendaftaran anggota">
                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between sm:p-6">
                    <div class="flex min-w-0 items-start gap-3.5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $statusCard['iconBox'] }}" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $statusCard['icon'] }}"/>
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] {{ $statusCard['text'] }}">Status pendaftaran</p>
                            <h2 class="mt-0.5 font-serif text-2xl font-medium {{ $statusCard['title'] }}">{{ $statusCard['label'] }}</h2>
                            <p class="mt-1 text-sm leading-6 {{ $statusCard['text'] }}">{{ $statusCard['description'] }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 self-start rounded-full px-3 py-1 text-[10px] font-extrabold uppercase tracking-wide ring-1 {{ $statusCard['badge'] }}">
                        {{ $anggota->statusLabel() }}
                    </span>
                </div>

                @if ($statusSaatIni === 'rejected')
                    <div class="border-t border-red-200/70 px-5 py-4 text-sm sm:px-6">
                        <p class="font-extrabold text-red-900">Catatan dari admin</p>
                        <p class="mt-1.5 whitespace-pre-line rounded-xl bg-white/70 px-3.5 py-3 leading-6 text-red-900/80">
                            {{ $anggota->catatan_verifikasi ?: 'Belum ada catatan dari admin.' }}
                        </p>
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-3 border-t border-black/5 px-5 py-3.5 text-xs sm:px-6">
                    @if ($statusSaatIni === 'approved')
                        <a href="{{ route('kartu.index') }}" class="inline-flex items-center gap-1.5 rounded-full bg-brand px-3.5 py-2 font-bold text-white transition hover:bg-brand-dark">
                            Lihat Kartu Anggota
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    @endif
                    <p class="text-brand-muted">
                        Status hanya dapat diubah oleh admin.
                        @if ($statusSaatIni !== 'approved')
                            Perubahan data akan diajukan untuk verifikasi ulang.
                        @else
                            Menyimpan perubahan akan mengajukan verifikasi ulang.
                        @endif
                    </p>
                </div>
            </section>
        @else
            <section class="mt-5 flex items-start gap-3.5 rounded-3xl border border-brand/15 bg-white px-5 py-4 shadow-sm sm:px-6">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-brand/10 text-brand" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14"/>
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-extrabold text-brand">Pendaftaran baru</p>
                    <p class="mt-1 text-xs leading-5 text-brand-muted">
                        Belum ada data anggota untuk akun ini. Setelah formulir dikirim, status akan menjadi
                        <span class="font-bold text-amber-700">Menunggu Verifikasi</span> sampai diperiksa admin.
                    </p>
                </div>
            </section>
        @endif

        <form id="form-anggota"
              action="{{ route('anggota.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="mt-6"
        >
            @csrf

            <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_330px]">
                <div class="space-y-6">
                    {{-- INFORMASI PRIBADI --}}
                    <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                        <div class="flex items-start gap-3 border-b border-brand-border/70 bg-[#FBFAF6] px-5 py-4 sm:px-6">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand text-xs font-extrabold text-white">1</span>
                            <div>
                                <h2 class="text-base font-black tracking-tight text-brand-deeper">Informasi Pribadi</h2>
                                <p class="mt-0.5 text-xs leading-5 text-brand-muted">Isi sesuai dokumen identitas yang berlaku.</p>
                            </div>
                        </div>

                        <div class="space-y-5 p-5 sm:p-6">
                            <div>
                                <label for="nama_anggota" class="flex items-center justify-between gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#4C5A53]">
                                    <span>Nama lengkap</span>
                                    <span class="rounded-full bg-red-50 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-red-600">Wajib</span>
                                </label>
                                <input
                                    id="nama_anggota"
                                    type="text"
                                    name="nama_anggota"
                                    value="{{ old('nama_anggota', $anggota?->nama_anggota) }}"
                                    maxlength="255"
                                    placeholder="Nama sesuai KTP atau kartu keluarga"
                                    autocomplete="name"
                                    required
                                    aria-required="true"
                                    @if ($errors->has('nama_anggota')) aria-invalid="true" aria-describedby="error-nama_anggota" @endif
                                    class="{{ $kelasInput }} mt-2 {{ $errors->has('nama_anggota') ? $kelasError : $kelasNormal }}"
                                >
                                @error('nama_anggota')
                                    <p id="error-nama_anggota" class="mt-2 flex items-start gap-1.5 text-xs font-semibold leading-5 text-red-600" role="alert">
                                        <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                                        </svg>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="tanggal_lahir" class="flex items-center justify-between gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#4C5A53]">
                                        <span>Tanggal lahir</span>
                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-slate-500">Opsional</span>
                                    </label>
                                    <input
                                        id="tanggal_lahir"
                                        type="date"
                                        name="tanggal_lahir"
                                        value="{{ old('tanggal_lahir', $anggota?->tanggal_lahir?->format('Y-m-d')) }}"
                                        max="{{ now()->format('Y-m-d') }}"
                                        autocomplete="bday"
                                        @if ($errors->has('tanggal_lahir')) aria-invalid="true" aria-describedby="error-tanggal_lahir" @endif
                                        class="{{ $kelasInput }} mt-2 {{ $errors->has('tanggal_lahir') ? $kelasError : $kelasNormal }}"
                                    >
                                    @error('tanggal_lahir')
                                        <p id="error-tanggal_lahir" class="mt-2 flex items-start gap-1.5 text-xs font-semibold leading-5 text-red-600" role="alert">
                                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                                            </svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jenis_kelamin" class="flex items-center justify-between gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#4C5A53]">
                                        <span>Jenis kelamin</span>
                                        <span class="rounded-full bg-red-50 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-red-600">Wajib</span>
                                    </label>
                                    <select
                                        id="jenis_kelamin"
                                        name="jenis_kelamin"
                                        required
                                        aria-required="true"
                                        @if ($errors->has('jenis_kelamin')) aria-invalid="true" aria-describedby="error-jenis_kelamin" @endif
                                        class="{{ $kelasInput }} mt-2 {{ $errors->has('jenis_kelamin') ? $kelasError : $kelasNormal }}"
                                    >
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="L" @selected(old('jenis_kelamin', $anggota?->jenis_kelamin) === 'L')>Laki-laki</option>
                                        <option value="P" @selected(old('jenis_kelamin', $anggota?->jenis_kelamin) === 'P')>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <p id="error-jenis_kelamin" class="mt-2 flex items-start gap-1.5 text-xs font-semibold leading-5 text-red-600" role="alert">
                                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                                            </svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ALAMAT --}}
                    <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                        <div class="flex items-start gap-3 border-b border-brand-border/70 bg-[#FBFAF6] px-5 py-4 sm:px-6">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand text-xs font-extrabold text-white">2</span>
                            <div>
                                <h2 class="text-base font-black tracking-tight text-brand-deeper">Alamat</h2>
                                <p class="mt-0.5 text-xs leading-5 text-brand-muted">Cukup pilih desa domisili, lalu isi RT dan RW.</p>
                            </div>
                        </div>

                        <div class="space-y-5 p-5 sm:p-6">
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="desa_id" class="flex items-center justify-between gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#4C5A53]">
                                        <span>Desa</span>
                                        <span class="rounded-full bg-red-50 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-red-600">Wajib</span>
                                    </label>
                                    <select
                                        id="desa_id"
                                        name="desa_id"
                                        required
                                        aria-required="true"
                                        @if ($errors->has('desa_id')) aria-invalid="true" aria-describedby="error-desa_id" @endif
                                        class="{{ $kelasInput }} mt-2 {{ $errors->has('desa_id') ? $kelasError : $kelasNormal }}"
                                    >
                                        <option value="">Pilih desa</option>
                                        @foreach ($desas as $desa)
                                            <option value="{{ $desa->id }}" @selected((string) old('desa_id', $anggota?->desa_id) === (string) $desa->id)>
                                                {{ $desa->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('desa_id')
                                        <p id="error-desa_id" class="mt-2 flex items-start gap-1.5 text-xs font-semibold leading-5 text-red-600" role="alert">
                                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                                            </svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @else
                                        <p class="mt-2 text-[11px] leading-5 text-brand-muted">Pilih salah satu dari 15 desa Kecamatan Ajibarang. Nama desa tidak dapat diisi manual.</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="rt" class="flex items-center justify-between gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#4C5A53]">
                                        <span>RT</span>
                                        <span class="rounded-full bg-red-50 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-red-600">Wajib</span>
                                    </label>
                                    <input
                                        id="rt"
                                        type="text"
                                        name="rt"
                                        value="{{ old('rt', $anggota?->rt) }}"
                                        maxlength="3"
                                        inputmode="numeric"
                                        pattern="[0-9]{1,3}"
                                        placeholder="Contoh: 03"
                                        required
                                        aria-required="true"
                                        @if ($errors->has('rt')) aria-invalid="true" aria-describedby="error-rt" @endif
                                        class="{{ $kelasInput }} mt-2 {{ $errors->has('rt') ? $kelasError : $kelasNormal }}"
                                    >
                                    @error('rt')
                                        <p id="error-rt" class="mt-2 flex items-start gap-1.5 text-xs font-semibold leading-5 text-red-600" role="alert">
                                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                                            </svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="rw" class="flex items-center justify-between gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#4C5A53]">
                                        <span>RW</span>
                                        <span class="rounded-full bg-red-50 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-red-600">Wajib</span>
                                    </label>
                                    <input
                                        id="rw"
                                        type="text"
                                        name="rw"
                                        value="{{ old('rw', $anggota?->rw) }}"
                                        maxlength="3"
                                        inputmode="numeric"
                                        pattern="[0-9]{1,3}"
                                        placeholder="Contoh: 02"
                                        required
                                        aria-required="true"
                                        @if ($errors->has('rw')) aria-invalid="true" aria-describedby="error-rw" @endif
                                        class="{{ $kelasInput }} mt-2 {{ $errors->has('rw') ? $kelasError : $kelasNormal }}"
                                    >
                                    @error('rw')
                                        <p id="error-rw" class="mt-2 flex items-start gap-1.5 text-xs font-semibold leading-5 text-red-600" role="alert">
                                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                                            </svg>
                                            <span>{{ $message }}</span>
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            @if ($desas->isEmpty())
                                <p class="rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-3 text-xs font-semibold leading-5 text-amber-900">
                                    Data desa belum tersedia. Hubungi admin MWC NU Ajibarang sebelum mengirim formulir.
                                </p>
                            @endif
                        </div>
                    </section>

                    {{-- KONTAK --}}
                    <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                        <div class="flex items-start gap-3 border-b border-brand-border/70 bg-[#FBFAF6] px-5 py-4 sm:px-6">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand text-xs font-extrabold text-white">3</span>
                            <div>
                                <h2 class="text-base font-black tracking-tight text-brand-deeper">Kontak</h2>
                                <p class="mt-0.5 text-xs leading-5 text-brand-muted">Nomor yang aktif digunakan untuk dihubungi admin.</p>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6">
                            <label for="no_hp" class="flex items-center justify-between gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#4C5A53]">
                                <span>Nomor HP / WhatsApp</span>
                                <span class="rounded-full bg-red-50 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-red-600">Wajib</span>
                            </label>
                            <input
                                id="no_hp"
                                type="tel"
                                name="no_hp"
                                value="{{ old('no_hp', $anggota?->no_hp) }}"
                                maxlength="20"
                                placeholder="Contoh: 081234567890"
                                inputmode="tel"
                                autocomplete="tel"
                                required
                                aria-required="true"
                                @if ($errors->has('no_hp')) aria-invalid="true" aria-describedby="error-no_hp" @endif
                                class="{{ $kelasInput }} mt-2 {{ $errors->has('no_hp') ? $kelasError : $kelasNormal }}"
                            >
                            @error('no_hp')
                                <p id="error-no_hp" class="mt-2 flex items-start gap-1.5 text-xs font-semibold leading-5 text-red-600" role="alert">
                                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @else
                                <p class="mt-2 text-[11px] leading-5 text-brand-muted">Gunakan nomor aktif. Admin dapat menghubungi nomor ini jika ada data yang perlu dikonfirmasi.</p>
                            @enderror
                        </div>
                    </section>

                    {{-- FOTO --}}
                    <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                        <div class="flex items-start gap-3 border-b border-brand-border/70 bg-[#FBFAF6] px-5 py-4 sm:px-6">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand text-xs font-extrabold text-white">4</span>
                            <div>
                                <h2 class="text-base font-black tracking-tight text-brand-deeper">Foto Anggota</h2>
                                <p class="mt-0.5 text-xs leading-5 text-brand-muted">Gunakan foto wajah yang jelas dan terlihat profesional.</p>
                            </div>
                        </div>

                        <div class="grid gap-5 p-5 sm:grid-cols-[180px_minmax(0,1fr)] sm:items-start sm:p-6">
                            <div class="mx-auto w-full max-w-[190px] sm:mx-0">
                                <div class="aspect-[4/5] overflow-hidden rounded-2xl border border-brand-border bg-[#F8F7F2]">
                                    <img
                                        id="foto-preview"
                                        @if ($fotoTersimpan) src="{{ asset('storage/'.$fotoTersimpan) }}" @endif
                                        alt="Pratinjau foto anggota"
                                        data-foto-preview
                                        data-existing-src="{{ $fotoTersimpan ? asset('storage/'.$fotoTersimpan) : '' }}"
                                        class="{{ $fotoTersimpan ? '' : 'hidden' }} h-full w-full object-cover"
                                    >
                                    <div id="foto-placeholder" class="{{ $fotoTersimpan ? 'hidden' : '' }} flex h-full flex-col items-center justify-center px-4 text-center">
                                        <svg class="h-8 w-8 text-brand/35" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.5-4.5 3 3L15 11l5 5M5 20h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1Z"/>
                                        </svg>
                                        <p class="mt-2 text-[11px] font-bold text-brand-muted">Belum ada foto</p>
                                        <p class="mt-1 text-[10px] leading-4 text-brand-muted/70">Pratinjau akan muncul setelah foto dipilih.</p>
                                    </div>
                                </div>
                                <button type="button" id="hapus-foto" class="mt-2 hidden w-full rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-[10.5px] font-bold text-red-700 transition hover:bg-red-100">
                                    Batalkan pilihan foto
                                </button>
                            </div>

                            <div>
                                <label for="foto" class="flex items-center justify-between gap-2 text-[11px] font-extrabold uppercase tracking-wider text-[#4C5A53]">
                                    <span>Unggah foto</span>
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-slate-500">Opsional</span>
                                </label>
                                <input
                                    id="foto"
                                    type="file"
                                    name="foto"
                                    accept="image/jpeg,image/png,image/webp"
                                    @if ($errors->has('foto')) aria-invalid="true" aria-describedby="error-foto" @endif
                                    class="mt-2 block w-full rounded-xl border border-brand-border bg-white px-3 py-2.5 text-xs text-brand-muted shadow-sm file:mr-3 file:rounded-lg file:border-0 file:bg-brand/10 file:px-3 file:py-2 file:text-xs file:font-bold file:text-brand hover:file:bg-brand/15"
                                >
                                <p class="mt-2 text-[11px] leading-5 text-brand-muted">
                                    Format JPG, PNG, atau WebP. Ukuran maksimal 2 MB.
                                    @if ($fotoTersimpan)
                                        Kosongkan jika tidak ingin mengganti foto yang tersimpan.
                                    @else
                                        Foto membantu admin mengenali data Anda.
                                    @endif
                                </p>
                                <p id="info-foto" class="mt-1.5 text-[11px] font-semibold text-brand-muted" role="status" aria-live="polite"></p>
                                @error('foto')
                                    <p id="error-foto" class="mt-2 flex items-start gap-1.5 text-xs font-semibold leading-5 text-red-600" role="alert">
                                        <svg class="mt-0.5 h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                                        </svg>
                                        <span>{{ $message }}</span>
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </section>
                </div>

                {{-- SIDEBAR --}}
                <aside class="space-y-5 lg:sticky lg:top-24">
                    <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                        <div class="border-b border-brand-border/70 px-5 py-4">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-accent">Alur pendaftaran</p>
                            <h2 class="mt-1 text-base font-black tracking-tight text-brand-deeper">Tiga langkah mudah</h2>
                        </div>

                        <ol class="space-y-4 p-5">
                            @php
                                $langkah = [
                                    1 => ['judul' => 'Lengkapi data', 'teks' => 'Isi data pribadi, desa/RT/RW, kontak, dan foto.'],
                                    2 => ['judul' => 'Verifikasi admin', 'teks' => 'Admin memeriksa kelengkapan dan keaslian data.'],
                                    3 => ['judul' => 'Kartu aktif', 'teks' => 'Kartu anggota dapat dilihat dan dicetak.'],
                                ];
                            @endphp

                            @foreach ($langkah as $nomor => $item)
                                @php
                                    $selesai = $nomor < $stepAktif;
                                    $aktif = $nomor === $stepAktif;
                                    $gagal = $aktif && $statusSaatIni === 'rejected';
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

                    <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                        <div class="border-b border-brand-border/70 px-5 py-4">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-accent">Sebelum mengirim</p>
                            <h2 class="mt-1 text-base font-black tracking-tight text-brand-deeper">Pastikan data sudah benar</h2>
                        </div>
                        <div class="space-y-3 p-5">
                            <p class="text-xs leading-5 text-brand-muted">
                                Periksa kembali nama, desa/RT/RW, nomor HP, dan foto. Setelah dikirim, status akan menjadi
                                <span class="font-bold text-amber-700">Menunggu Verifikasi</span>.
                            </p>

                            <button type="submit"
                                    data-submit-anggota
                                    class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-brand/20 transition hover:-translate-y-0.5 hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 disabled:cursor-wait disabled:opacity-60">
                                {{ $anggota ? 'Simpan & Ajukan Verifikasi' : 'Kirim Pendaftaran' }}
                                <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/>
                                </svg>
                            </button>

                            <a href="{{ route('home') }}" class="block rounded-xl border border-brand-border bg-white px-4 py-2.5 text-center text-xs font-bold text-brand-muted transition hover:border-brand hover:text-brand">
                                Kembali ke Beranda
                            </a>

                            <p class="text-[10.5px] leading-5 text-brand-muted/80">
                                Dengan mengirim formulir, Anda menyatakan data yang diisi benar dan dapat diverifikasi oleh admin MWC NU Ajibarang.
                            </p>
                        </div>
                    </section>
                </aside>
            </div>
        </form>
    </div>

    {{-- KONFIRMASI PENGIRIMAN --}}
    <div id="modal-konfirmasi" class="fixed inset-0 z-[70] hidden" role="dialog" aria-modal="true" aria-labelledby="judul-konfirmasi" aria-hidden="true">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm" data-modal-close></div>

        <div class="relative mx-auto flex min-h-full max-w-lg items-center px-4 py-8">
            <div class="w-full overflow-hidden rounded-3xl bg-white shadow-2xl">
                <div class="border-b border-brand-border/70 bg-[#FBFAF6] px-5 py-5 sm:px-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-accent">Konfirmasi</p>
                            <h2 id="judul-konfirmasi" class="mt-1 font-serif text-2xl font-medium text-brand-deeper">Periksa data Anda</h2>
                            <p class="mt-1 text-xs leading-5 text-brand-muted">Pastikan data berikut sudah benar sebelum dikirim.</p>
                        </div>
                        <button type="button" data-modal-close aria-label="Tutup konfirmasi" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-brand-border bg-white text-brand-muted transition hover:text-brand">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 18 12-12M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="max-h-[60vh] overflow-y-auto px-5 py-5 sm:px-6">
                    <dl class="space-y-3.5 text-sm">
                        <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3">
                            <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Nama</dt>
                            <dd id="ringkasan-nama" class="font-semibold text-neutral-900">—</dd>
                        </div>
                        <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3">
                            <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Tanggal lahir</dt>
                            <dd id="ringkasan-tanggal" class="font-semibold text-neutral-900">—</dd>
                        </div>
                        <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3">
                            <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Jenis kelamin</dt>
                            <dd id="ringkasan-jk" class="font-semibold text-neutral-900">—</dd>
                        </div>
                        <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3">
                            <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Nomor HP</dt>
                            <dd id="ringkasan-hp" class="font-semibold text-neutral-900">—</dd>
                        </div>
                        <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3">
                            <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Desa</dt>
                            <dd id="ringkasan-desa" class="font-semibold text-neutral-900">—</dd>
                        </div>
                        <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3">
                            <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">RT / RW</dt>
                            <dd id="ringkasan-rt-rw" class="font-semibold text-neutral-900">—</dd>
                        </div>
                        <div class="grid grid-cols-[110px_minmax(0,1fr)] gap-3">
                            <dt class="text-[10px] font-extrabold uppercase tracking-wider text-brand-muted">Foto</dt>
                            <dd id="ringkasan-foto" class="font-semibold text-neutral-900">—</dd>
                        </div>
                    </dl>

                    <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 px-3.5 py-3 text-[11px] leading-5 text-amber-900">
                        Setelah dikirim, status pendaftaran akan menjadi <strong>Menunggu Verifikasi</strong>. Kartu anggota baru dapat dicetak setelah disetujui admin.
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-2 border-t border-brand-border/70 bg-[#FBFAF6] px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                    <button type="button" data-modal-close class="rounded-xl border border-brand-border bg-white px-4 py-2.5 text-xs font-bold text-brand-muted transition hover:border-brand hover:text-brand">
                        Periksa Kembali
                    </button>
                    <button type="button" id="tombol-konfirmasi" class="inline-flex items-center justify-center gap-2 rounded-xl bg-brand px-5 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:bg-brand-dark">
                        Kirim Pendaftaran
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('form-anggota');
                const modal = document.getElementById('modal-konfirmasi');
                const tombolKonfirmasi = document.getElementById('tombol-konfirmasi');
                const tombolSubmit = form?.querySelector('[data-submit-anggota]');
                const fotoInput = document.getElementById('foto');
                const fotoPreview = document.getElementById('foto-preview');
                const fotoPlaceholder = document.getElementById('foto-placeholder');
                const hapusFoto = document.getElementById('hapus-foto');
                const infoFoto = document.getElementById('info-foto');
                const fotoTersimpan = fotoPreview?.dataset.existingSrc || '';

                const nilai = (id) => document.getElementById(id)?.value.trim() || '';
                const teksOpsi = (select) => select?.selectedOptions?.[0]?.text?.trim() || '';

                const formatTanggal = (tanggal) => {
                    if (!tanggal) return 'Belum diisi';

                    const bagian = tanggal.split('-');

                    if (bagian.length !== 3) return tanggal;

                    const date = new Date(Number(bagian[0]), Number(bagian[1]) - 1, Number(bagian[2]));

                    return Number.isNaN(date.getTime())
                        ? tanggal
                        : date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                };

                const aturPreview = (src) => {
                    if (!fotoPreview || !fotoPlaceholder) return;

                    if (src) {
                        fotoPreview.src = src;
                        fotoPreview.classList.remove('hidden');
                        fotoPlaceholder.classList.add('hidden');
                    } else {
                        fotoPreview.removeAttribute('src');
                        fotoPreview.classList.add('hidden');
                        fotoPlaceholder.classList.remove('hidden');
                    }
                };

                const aturInfoFoto = (pesan, error = false) => {
                    if (!infoFoto) return;

                    infoFoto.textContent = pesan;
                    infoFoto.className = 'mt-1.5 text-[11px] font-semibold ' + (error ? 'text-red-600' : 'text-brand-muted');
                };

                fotoInput?.addEventListener('change', () => {
                    const file = fotoInput.files?.[0];

                    if (!file) {
                        aturPreview(fotoTersimpan);
                        hapusFoto?.classList.add('hidden');
                        aturInfoFoto('');
                        return;
                    }

                    const tipeDiizinkan = ['image/jpeg', 'image/png', 'image/webp'];

                    if (!tipeDiizinkan.includes(file.type)) {
                        fotoInput.value = '';
                        aturPreview(fotoTersimpan);
                        hapusFoto?.classList.add('hidden');
                        aturInfoFoto('Format foto harus JPG, PNG, atau WebP.', true);
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        fotoInput.value = '';
                        aturPreview(fotoTersimpan);
                        hapusFoto?.classList.add('hidden');
                        aturInfoFoto('Ukuran foto melebihi 2 MB. Pilih foto lain.', true);
                        return;
                    }

                    const reader = new FileReader();

                    reader.addEventListener('load', () => {
                        aturPreview(reader.result);
                        hapusFoto?.classList.remove('hidden');
                        aturInfoFoto(file.name + ' · ' + (file.size / 1024).toFixed(0) + ' KB');
                    });

                    reader.readAsDataURL(file);
                });

                hapusFoto?.addEventListener('click', () => {
                    if (fotoInput) fotoInput.value = '';

                    aturPreview(fotoTersimpan);
                    hapusFoto.classList.add('hidden');
                    aturInfoFoto(fotoTersimpan ? 'Kembali menggunakan foto yang tersimpan.' : 'Pilihan foto dibatalkan.');
                });

                const isiRingkasan = () => {
                    const selectJk = document.getElementById('jenis_kelamin');
                    const selectDesa = document.getElementById('desa_id');

                    document.getElementById('ringkasan-nama').textContent = nilai('nama_anggota') || 'Belum diisi';
                    document.getElementById('ringkasan-tanggal').textContent = formatTanggal(nilai('tanggal_lahir'));
                    document.getElementById('ringkasan-jk').textContent = teksOpsi(selectJk) || 'Belum dipilih';
                    document.getElementById('ringkasan-hp').textContent = nilai('no_hp') || 'Belum diisi';

                    const namaDesa = selectDesa?.value ? teksOpsi(selectDesa) : '';
                    document.getElementById('ringkasan-desa').textContent = namaDesa || 'Belum dipilih';

                    const rt = nilai('rt');
                    const rw = nilai('rw');
                    document.getElementById('ringkasan-rt-rw').textContent =
                        rt || rw ? `RT ${rt || '—'} / RW ${rw || '—'}` : 'Belum diisi';

                    let statusFoto = 'Belum ada foto';
                    if (fotoInput?.files?.length) {
                        statusFoto = 'Foto baru dipilih';
                    } else if (fotoTersimpan) {
                        statusFoto = 'Menggunakan foto yang tersimpan';
                    }

                    document.getElementById('ringkasan-foto').textContent = statusFoto;
                };

                const bukaModal = () => {
                    if (!modal) return;

                    isiRingkasan();
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('overflow-hidden');
                    tombolKonfirmasi?.focus();
                };

                const tutupModal = () => {
                    if (!modal) return;

                    modal.classList.add('hidden');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('overflow-hidden');
                    tombolSubmit?.focus();
                };

                form?.addEventListener('submit', (event) => {
                    if (form.dataset.confirmed === 'true') {
                        if (tombolSubmit) {
                            tombolSubmit.disabled = true;
                            tombolSubmit.textContent = 'Mengirim data...';
                        }

                        return;
                    }

                    event.preventDefault();
                    bukaModal();
                });

                tombolKonfirmasi?.addEventListener('click', () => {
                    if (!form) return;

                    form.dataset.confirmed = 'true';
                    tutupModal();

                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                });

                modal?.querySelectorAll('[data-modal-close]').forEach((tombol) => {
                    tombol.addEventListener('click', tutupModal);
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                        tutupModal();
                    }
                });
            });
        </script>
    @endpush
</x-member-layout>

<x-admin-layout title="Tambah Anggota">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <nav class="flex flex-wrap items-center gap-1.5 text-[11px] font-semibold text-slate-400" aria-label="Breadcrumb">
                    <a href="{{ route('admin.index') }}" class="hover:text-brand">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.anggota.index') }}" class="hover:text-brand">Kelola Anggota</a>
                    <span>/</span>
                    <span class="text-brand">Tambah Anggota</span>
                </nav>
                <h1 class="mt-1.5 font-serif text-2xl font-semibold tracking-tight text-brand-deeper sm:text-3xl">Tambah Data Anggota</h1>
                <p class="mt-1.5 max-w-2xl text-sm text-brand-muted">
                    Masukkan data anggota yang terhubung dengan akun pengguna. Data baru akan berstatus menunggu verifikasi.
                </p>
            </div>

            <a href="{{ route('admin.anggota.index') }}"
               class="inline-flex w-fit items-center gap-2 rounded-xl border border-brand-border bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition hover:border-brand hover:text-brand">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-[1500px] px-4 py-6 sm:px-6 lg:px-8">
        @if ($users->isEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-900">
                <p class="font-bold">Belum ada akun pengguna yang bisa dihubungkan.</p>
                <p class="mt-1 leading-relaxed">
                    Setiap data anggota wajib terhubung dengan satu akun pengguna (kolom <span class="font-mono text-xs">user_id</span> bersifat unik).
                    Semua akun yang tersedia sudah memiliki data anggota. Minta calon anggota mendaftar melalui halaman registrasi publik terlebih dahulu,
                    lalu kembali ke halaman ini untuk menambahkan datanya.
                </p>
            </div>
        @endif

        <form action="{{ route('admin.anggota.store') }}" method="POST" enctype="multipart/form-data"
              class="mt-5 space-y-6" id="form-anggota">
            @csrf

            <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-6">
                    {{-- AKUN & IDENTITAS --}}
                    <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                            <h2 class="text-base font-black tracking-tight text-slate-900">Akun &amp; Identitas</h2>
                            <p class="mt-0.5 text-xs text-brand-muted">Hubungkan data anggota dengan akun pengguna yang sudah terdaftar.</p>
                        </div>

                        <div class="grid gap-5 p-5 sm:p-6">
                            <div>
                                <label for="user_id" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                    Akun Pengguna <span class="text-red-500">*</span>
                                </label>
                                <select id="user_id" name="user_id" required
                                        @disabled($users->isEmpty())
                                        class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand disabled:cursor-not-allowed disabled:bg-slate-100">
                                    <option value="" disabled {{ old('user_id') ? '' : 'selected' }}>Pilih akun pengguna</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>
                                            {{ $user->name }} — {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                @else
                                    <p class="mt-1 text-[11px] leading-relaxed text-slate-400">
                                        Hanya akun yang belum memiliki data anggota yang ditampilkan.
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="nama_anggota" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_anggota" name="nama_anggota" required maxlength="255"
                                       value="{{ old('nama_anggota') }}"
                                       placeholder="Nama lengkap anggota"
                                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                @error('nama_anggota')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label for="jenis_kelamin" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" required
                                            class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                        <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih jenis kelamin</option>
                                        <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                                        <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="tanggal_lahir" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                           value="{{ old('tanggal_lahir') }}" max="{{ now()->toDateString() }}"
                                           class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                    @error('tanggal_lahir')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div>
                                <label for="no_hp" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                    Nomor HP <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="no_hp" name="no_hp" required maxlength="20"
                                       value="{{ old('no_hp') }}"
                                       placeholder="Contoh: 081234567890"
                                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                @error('no_hp')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid gap-5 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label for="desa_id" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                        Desa <span class="text-red-500">*</span>
                                    </label>
                                    <select id="desa_id" name="desa_id" required
                                            class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                        <option value="" disabled {{ old('desa_id') ? '' : 'selected' }}>Pilih desa</option>
                                        @foreach ($desas as $desa)
                                            <option value="{{ $desa->id }}" @selected((string) old('desa_id') === (string) $desa->id)>
                                                {{ $desa->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('desa_id')
                                        <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                    @else
                                        <p class="mt-1 text-[11px] leading-relaxed text-slate-400">
                                            Pilih satu dari 15 desa Kecamatan Ajibarang.
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="rt" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                        RT <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="rt" name="rt" required maxlength="3"
                                           inputmode="numeric" pattern="[0-9]{1,3}"
                                           value="{{ old('rt') }}"
                                           placeholder="Contoh: 03"
                                           class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                    @error('rt')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="rw" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                        RW <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="rw" name="rw" required maxlength="3"
                                           inputmode="numeric" pattern="[0-9]{1,3}"
                                           value="{{ old('rw') }}"
                                           placeholder="Contoh: 02"
                                           class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                    @error('rw')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6 xl:sticky xl:top-24">
                    {{-- FOTO --}}
                    <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-5 py-4">
                            <h2 class="text-base font-black tracking-tight text-slate-900">Foto Anggota</h2>
                            <p class="mt-0.5 text-xs text-brand-muted">Opsional. JPG, PNG, atau WebP maksimal 2 MB.</p>
                        </div>
                        <div class="space-y-3 p-5">
                            <div id="preview-kosong" class="flex aspect-[4/5] max-h-56 flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 text-center">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-emerald-50 text-brand">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/>
                                    </svg>
                                </span>
                                <p class="mt-2 text-xs font-bold text-slate-600">Belum ada foto</p>
                                <p class="mt-1 text-[10.5px] text-slate-400">Pilih foto formal anggota.</p>
                            </div>

                            <div id="preview-wrap" class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                <img id="preview-foto" src="" alt="Pratinjau foto anggota"
                                     class="aspect-[4/5] max-h-64 w-full object-cover">
                            </div>

                            <label for="foto"
                                   class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-brand-border bg-white px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:border-brand hover:text-brand">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0L8 8m4-4 4 4M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>
                                </svg>
                                Pilih Foto
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-xs file:font-bold file:text-emerald-800">

                            <p id="info-foto" class="text-[10.5px] leading-relaxed text-slate-400"></p>
                            @error('foto')<p class="text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </section>

                    {{-- STATUS & AKSI --}}
                    <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-5 py-4">
                            <h2 class="text-base font-black tracking-tight text-slate-900">Verifikasi</h2>
                            <p class="mt-0.5 text-xs text-brand-muted">Alur status mengikuti sistem yang sudah ada.</p>
                        </div>
                        <div class="space-y-3 p-5">
                            <div class="flex items-center justify-between rounded-xl bg-amber-50 px-3 py-2.5">
                                <span class="text-xs font-semibold text-amber-900">Status awal</span>
                                <span class="rounded-full bg-amber-200 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-amber-900">
                                    Menunggu
                                </span>
                            </div>
                            <p class="text-[10.5px] leading-relaxed text-slate-400">
                                Setelah disimpan, anggota akan berstatus <span class="font-semibold text-slate-600">pending</span>.
                                Admin dapat menyetujui atau menolak melalui halaman Kelola Anggota.
                            </p>

                            <button type="submit" id="tombol-simpan-anggota" @disabled($users->isEmpty())
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-brand px-4 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-brand-dark disabled:cursor-not-allowed disabled:bg-slate-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="m5 12 4 4L19 6"/>
                                </svg>
                                Simpan Data Anggota
                            </button>

                            <p id="indikator-simpan" class="hidden rounded-lg bg-sky-50 px-3 py-2 text-center text-[11px] font-semibold text-sky-800">
                                Menyimpan data anggota...
                            </p>

                            <a href="{{ route('admin.anggota.index') }}"
                               class="block rounded-xl border border-slate-200 px-4 py-2.5 text-center text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                                Batal
                            </a>
                        </div>
                    </section>
                </aside>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('form-anggota');
                const inputFoto = document.getElementById('foto');
                const previewWrap = document.getElementById('preview-wrap');
                const previewKosong = document.getElementById('preview-kosong');
                const previewFoto = document.getElementById('preview-foto');
                const infoFoto = document.getElementById('info-foto');
                const tombolSimpan = document.getElementById('tombol-simpan-anggota');
                const indikator = document.getElementById('indikator-simpan');

                inputFoto?.addEventListener('change', () => {
                    const file = inputFoto.files?.[0];

                    if (!file) {
                        return;
                    }

                    const tipeDiizinkan = ['image/jpeg', 'image/png', 'image/webp'];
                    const pesan = [];

                    if (!tipeDiizinkan.includes(file.type)) {
                        pesan.push('Tipe berkas tidak didukung.');
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        pesan.push('Ukuran melebihi 2 MB.');
                    }

                    if (pesan.length > 0) {
                        if (infoFoto) {
                            infoFoto.textContent = pesan.join(' ') + ' Pilih berkas lain.';
                            infoFoto.className = 'text-[10.5px] font-semibold leading-relaxed text-red-600';
                        }
                        return;
                    }

                    const reader = new FileReader();

                    reader.addEventListener('load', () => {
                        if (previewFoto) previewFoto.src = reader.result;
                        if (previewWrap) previewWrap.classList.remove('hidden');
                        if (previewKosong) previewKosong.classList.add('hidden');
                        if (infoFoto) {
                            infoFoto.textContent = file.name + ' · ' + (file.size / 1024).toFixed(0) + ' KB';
                            infoFoto.className = 'text-[10.5px] leading-relaxed text-emerald-700';
                        }
                    });

                    reader.readAsDataURL(file);
                });

                form?.addEventListener('submit', () => {
                    if (form.dataset.submitting === 'true') {
                        return;
                    }

                    form.dataset.submitting = 'true';

                    if (tombolSimpan) {
                        tombolSimpan.disabled = true;
                        tombolSimpan.classList.add('opacity-60', 'cursor-wait');
                    }

                    if (indikator) {
                        indikator.classList.remove('hidden');
                    }
                });
            });
        </script>
    @endpush
</x-admin-layout>

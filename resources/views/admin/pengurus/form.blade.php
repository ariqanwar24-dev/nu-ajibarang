<x-admin-layout :title="$mode === 'edit' ? 'Edit Pengurus' : 'Tambah Pengurus'">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <nav class="flex flex-wrap items-center gap-1.5 text-[11px] font-semibold text-slate-400" aria-label="Breadcrumb">
                    <a href="{{ route('admin.index') }}" class="hover:text-brand">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.pengurus.index') }}" class="hover:text-brand">Kelola Pengurus</a>
                    <span>/</span>
                    <span class="text-brand">{{ $mode === 'edit' ? 'Edit' : 'Tambah' }}</span>
                </nav>
                <h1 class="mt-1.5 font-serif text-2xl font-semibold tracking-tight text-brand-deeper sm:text-3xl">
                    {{ $mode === 'edit' ? 'Edit Pengurus' : 'Tambah Pengurus' }}
                </h1>
                <p class="mt-1.5 text-sm text-brand-muted">Lengkapi identitas, jabatan, kategori, dan foto pengurus.</p>
            </div>
            <a href="{{ route('admin.pengurus.index') }}"
               class="inline-flex w-fit items-center gap-2 rounded-xl border border-brand-border bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition hover:border-brand hover:text-brand">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
        @if ($mode === 'edit' && $pengurus->is_demo)
            <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs leading-relaxed text-amber-900">
                <strong>Data contoh:</strong> data ini berasal dari seeder demonstrasi. Menjalankan ulang
                <span class="font-mono text-[11px]">PengurusSeeder</span> dapat memperbarui kembali data ini.
            </div>
        @endif

        <form method="POST"
              action="{{ $mode === 'edit' ? route('admin.pengurus.update', $pengurus) : route('admin.pengurus.store') }}"
              enctype="multipart/form-data"
              class="space-y-6 rounded-3xl border border-brand-border bg-white p-5 shadow-sm sm:p-7">
            @csrf
            @if ($mode === 'edit')
                @method('PUT')
            @endif

            <div>
                <label for="nama" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                    Nama Pengurus <span class="text-red-500">*</span>
                </label>
                <input id="nama" name="nama" value="{{ old('nama', $pengurus->nama) }}" required maxlength="255"
                       placeholder="Nama lengkap pengurus"
                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                @error('nama')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="jabatan" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                    Jabatan <span class="text-red-500">*</span>
                </label>
                <input id="jabatan" name="jabatan" value="{{ old('jabatan', $pengurus->jabatan) }}" required maxlength="255"
                       placeholder="Contoh: Ketua Tanfidziyah"
                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                @error('jabatan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-5 sm:grid-cols-[minmax(0,1fr)_140px]">
                <div>
                    <label for="tingkatan" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                        Kategori / Banom NU <span class="text-red-500">*</span>
                    </label>
                    <select id="tingkatan" name="tingkatan" required
                            class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                        <option value="" disabled {{ old('tingkatan', $pengurus->tingkatan) ? '' : 'selected' }}>Pilih kategori</option>

                        <optgroup label="Kategori Utama">
                            @foreach ($kategori as $value => $data)
                                @continue($value === 'banom')
                                <option value="{{ $value }}" @selected(old('tingkatan', $pengurus->tingkatan) === $value)>
                                    {{ $data['judul'] }}
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="Banom NU">
                            <option value="banom" @selected(old('tingkatan', $pengurus->tingkatan) === 'banom')>
                                Banom NU (umum)
                            </option>
                            @foreach (['GP Ansor', 'Muslimat NU', 'Fatayat NU', 'IPNU', 'IPPNU', 'Pagar Nusa', 'ISNU', 'Pergunu'] as $banom)
                                <option value="{{ $banom }}" @selected(old('tingkatan', $pengurus->tingkatan) === $banom)>
                                    {{ $banom }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                    <p class="mt-1.5 text-[11px] leading-relaxed text-slate-400">
                        Pilih kategori utama, atau pilih unit Banom NU agar nama badan otonom tampil pada kartu.
                    </p>
                    @error('tingkatan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="urutan" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                        Urutan
                    </label>
                    <input id="urutan" name="urutan" type="number" min="0" max="9999"
                           value="{{ old('urutan', $pengurus->urutan ?? 0) }}"
                           class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                    <p class="mt-1.5 text-[11px] leading-relaxed text-slate-400">Angka kecil tampil lebih dahulu.</p>
                    @error('urutan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="foto" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                    Foto Pengurus
                    <span class="font-medium normal-case tracking-normal text-slate-400">(opsional, JPG/PNG/WebP maks. 2 MB)</span>
                </label>

                @if ($mode === 'edit' && $pengurus->foto_url)
                    <div class="mb-3 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                        <img src="{{ $pengurus->foto_url }}" alt="Foto saat ini"
                             class="h-24 w-20 rounded-xl object-cover object-top">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-700">Foto saat ini</p>
                            <p class="mt-0.5 text-xs leading-relaxed text-slate-500">
                                Pilih berkas baru untuk mengganti foto. Foto lama akan dihapus setelah penyimpanan berhasil.
                            </p>
                            <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-red-700">
                                <input type="checkbox" name="hapus_foto" value="1" @checked(old('hapus_foto'))
                                       class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                Hapus foto saat ini
                            </label>
                        </div>
                    </div>
                @endif

                <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp"
                       class="block w-full rounded-xl border border-slate-300 p-2 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:font-bold file:text-emerald-800">
                <p class="mt-1.5 text-[11px] leading-relaxed text-slate-400">
                    Gunakan foto potret dengan ukuran minimal 300 × 300 piksel. Jika foto belum tersedia, halaman publik akan menampilkan inisial nama.
                </p>
                @error('foto')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.pengurus.index') }}"
                   class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-xl bg-brand px-6 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-brand-dark">
                    {{ $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Pengurus' }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

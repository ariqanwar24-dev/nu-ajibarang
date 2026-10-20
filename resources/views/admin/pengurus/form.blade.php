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
                <p class="mt-1.5 text-sm text-brand-muted">Lengkapi identitas, jabatan, dan tingkatan pengurus.</p>
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
                       placeholder="Contoh: Ketua"
                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                @error('jabatan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="tingkatan" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                    Tingkatan / Banom <span class="text-red-500">*</span>
                </label>
                @php
                    $pilihan = [
                        'mustasyar' => 'Mustasyar',
                        'syuriyah' => 'Syuriyah',
                        'tanfidziyah' => 'Tanfidziyah',
                        'banom' => 'Banom',
                        'GP Ansor' => 'GP Ansor',
                        'Muslimat NU' => 'Muslimat NU',
                        'Fatayat NU' => 'Fatayat NU',
                        'IPNU' => 'IPNU',
                        'IPPNU' => 'IPPNU',
                        'Pagar Nusa' => 'Pagar Nusa',
                        'ISNU' => 'ISNU',
                        'Pergunu' => 'Pergunu',
                    ];
                @endphp
                <select id="tingkatan" name="tingkatan" required
                        class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                    <option value="" disabled {{ old('tingkatan', $pengurus->tingkatan) ? '' : 'selected' }}>Pilih tingkatan</option>
                    @foreach ($pilihan as $value => $label)
                        <option value="{{ $value }}" @selected(old('tingkatan', $pengurus->tingkatan) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('tingkatan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="foto" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                    Foto Pengurus
                    <span class="font-medium normal-case tracking-normal text-slate-400">(opsional, JPG/PNG/WebP maks. 2 MB)</span>
                </label>

                @if ($mode === 'edit' && $pengurus->foto)
                    <div class="mb-3 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                        <img src="{{ asset('storage/'.$pengurus->foto) }}" alt="Foto saat ini" class="h-20 w-20 rounded-xl object-cover">
                        <p class="text-xs leading-relaxed text-slate-500">Foto saat ini. Pilih berkas baru jika ingin menggantinya.</p>
                    </div>
                @endif

                <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp"
                       class="block w-full rounded-xl border border-slate-300 p-2 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:font-bold file:text-emerald-800">
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

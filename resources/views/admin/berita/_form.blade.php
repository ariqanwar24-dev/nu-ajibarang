@php
    $isEdit = $berita !== null;
    $statusForm = old('status', ($berita?->isPublished() || $berita?->isPending())
        ? \App\Models\Berita::STATUS_PENDING
        : \App\Models\Berita::STATUS_DRAFT);
    $statusSaatIni = $berita?->status ?? \App\Models\Berita::STATUS_DRAFT;
    $labelStatus = [
        \App\Models\Berita::STATUS_DRAFT => 'Draft',
        \App\Models\Berita::STATUS_PENDING => 'Menunggu Review',
        \App\Models\Berita::STATUS_PUBLISHED => 'Terbit',
        \App\Models\Berita::STATUS_REJECTED => 'Ditolak',
    ];
    $kelasStatus = [
        \App\Models\Berita::STATUS_DRAFT => 'bg-slate-200 text-slate-700',
        \App\Models\Berita::STATUS_PENDING => 'bg-amber-100 text-amber-800',
        \App\Models\Berita::STATUS_PUBLISHED => 'bg-emerald-100 text-emerald-800',
        \App\Models\Berita::STATUS_REJECTED => 'bg-red-100 text-red-700',
    ];
    $gambarTersimpan = $berita?->gambar;
    $galeriTersimpan = $berita?->galeri ?? collect();
    $galeriPenuh = $galeriTersimpan->count() >= \App\Models\Berita::MAX_GALERI;
    $galeriMelebihiBatas = $galeriTersimpan->count() > \App\Models\Berita::MAX_GALERI;
    $kategoriTersimpan = old('kategori', $berita?->kategori);
    $daftarKategori = ['Pengajian', 'Kegiatan Organisasi', 'Pendidikan', 'Sosial', 'Banom', 'Pengumuman', 'Prestasi'];
@endphp

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <style>
        #peta-lokasi { min-height: 320px; z-index: 0; }
        .leaflet-container { font-family: inherit; }
        .leaflet-control-attribution { font-size: 9px !important; }
    </style>
@endpush

<form id="form-berita"
      action="{{ $isEdit ? route('admin.berita.update', $berita) : route('admin.berita.store') }}"
      method="POST"
      enctype="multipart/form-data"
      class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <input type="hidden" name="status" id="status-berita" value="{{ $statusForm }}">

    <div class="mx-auto max-w-[1500px] px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1fr)_368px]">

            {{-- KOLOM KONTEN --}}
            <div class="space-y-6">
                {{-- KONTEN UTAMA --}}
                <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                        <h2 class="text-base font-black tracking-tight text-slate-900">Konten Berita</h2>
                        <p class="mt-0.5 text-xs text-brand-muted">Tulis judul, ringkasan, dan isi berita yang akan dipublikasikan.</p>
                    </div>

                    <div class="grid gap-5 p-5 sm:p-6">
                        <div>
                            <label for="judul" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                Judul Berita <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" required maxlength="255"
                                   value="{{ old('judul', $berita?->judul) }}"
                                   placeholder="Contoh: Pengajian Rutin MWC NU Ajibarang Bulan Ini"
                                   class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                            <div class="mt-1 flex items-center justify-between gap-2">
                                @error('judul')
                                    <p class="text-xs font-semibold text-red-600">{{ $message }}</p>
                                @else
                                    <p class="text-[11px] text-slate-400">Gunakan judul yang jelas dan informatif.</p>
                                @enderror
                                <span class="shrink-0 text-[10.5px] text-slate-400"><span id="hitung-judul">{{ mb_strlen(old('judul', $berita?->judul ?? '')) }}</span>/255</span>
                            </div>
                        </div>

                        <div>
                            <label for="slug" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                Slug URL
                            </label>
                            <div class="flex overflow-hidden rounded-xl border border-slate-300 shadow-sm focus-within:border-brand focus-within:ring-1 focus-within:ring-brand">
                                <span class="flex items-center bg-slate-50 px-3 text-[11px] font-semibold text-slate-500">/berita/</span>
                                <input type="text" id="slug" name="slug" maxlength="255"
                                       value="{{ old('slug', $berita?->slug) }}"
                                       placeholder="slug-otomatis-dari-judul"
                                       class="w-full border-0 bg-white px-3 py-2.5 text-sm focus:ring-0">
                            </div>
                            <div class="mt-1 flex items-start justify-between gap-2">
                                @error('slug')
                                    <p class="text-xs font-semibold text-red-600">{{ $message }}</p>
                                @else
                                    <p class="text-[11px] leading-relaxed text-slate-400">Dibuat otomatis dari judul. Anda tetap bisa mengubahnya secara manual.</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="ringkasan" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                Ringkasan Berita
                            </label>
                            <textarea id="ringkasan" name="ringkasan" rows="3" maxlength="600"
                                      placeholder="Ringkasan singkat yang tampil pada daftar berita..."
                                      class="w-full rounded-xl border-slate-300 text-sm leading-relaxed shadow-sm focus:border-brand focus:ring-brand">{{ old('ringkasan', $berita?->ringkasan) }}</textarea>
                            <div class="mt-1 flex items-center justify-between gap-2">
                                @error('ringkasan')
                                    <p class="text-xs font-semibold text-red-600">{{ $message }}</p>
                                @else
                                    <p class="text-[11px] text-slate-400">Opsional. Jika kosong, daftar berita akan memakai potongan isi berita.</p>
                                @enderror
                                <span class="shrink-0 text-[10.5px] text-slate-400"><span id="hitung-ringkasan">{{ mb_strlen(old('ringkasan', $berita?->ringkasan ?? '')) }}</span>/600</span>
                            </div>
                        </div>

                        <div>
                            <label for="isi" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                Isi Berita <span class="text-red-500">*</span>
                            </label>
                            <textarea id="isi" name="isi" rows="16" required
                                      placeholder="Tulis isi berita di sini. Pisahkan antarparagraf dengan enter..."
                                      class="w-full rounded-xl border-slate-300 text-sm leading-7 shadow-sm focus:border-brand focus:ring-brand">{{ old('isi', $berita?->isi) }}</textarea>
                            @error('isi')
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @else
                                <p class="mt-1 text-[11px] text-slate-400">Editor teks sederhana (teks biasa). Baris baru akan dipertahankan saat berita ditampilkan.</p>
                            @enderror
                        </div>
                    </div>
                </section>

                {{-- LOKASI ACARA --}}
                <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-base font-black tracking-tight text-slate-900">Lokasi Acara</h2>
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[9.5px] font-extrabold uppercase tracking-wide text-slate-500">Opsional</span>
                            </div>
                            <p class="mt-0.5 text-xs text-brand-muted">
                                Isi jika berita berkaitan dengan pengajian, kegiatan, atau agenda yang memiliki tempat.
                            </p>
                        </div>
                        <span class="rounded-full bg-sky-50 px-2.5 py-1 text-[9.5px] font-extrabold uppercase tracking-wide text-sky-700">
                            Peta &amp; petunjuk arah
                        </span>
                    </div>

                    <div class="grid gap-5 p-5 sm:p-6">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="lokasi_nama" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Nama Tempat</label>
                                <input type="text" id="lokasi_nama" name="lokasi_nama" maxlength="255"
                                       value="{{ old('lokasi_nama', $berita?->lokasi_nama) }}"
                                       placeholder="Contoh: Masjid Baitul Muttaqin"
                                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                @error('lokasi_nama')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="lokasi_desa" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Desa</label>
                                <input type="text" id="lokasi_desa" name="lokasi_desa" maxlength="100" list="daftar-desa"
                                       value="{{ old('lokasi_desa', $berita?->lokasi_desa) }}"
                                       placeholder="Pilih atau tulis nama desa"
                                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                <datalist id="daftar-desa">
                                    @foreach ($desas ?? [] as $desa)
                                        <option value="{{ $desa }}"></option>
                                    @endforeach
                                </datalist>
                                @error('lokasi_desa')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="lokasi_alamat" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Alamat Lengkap</label>
                            <textarea id="lokasi_alamat" name="lokasi_alamat" rows="2" maxlength="1000"
                                      placeholder="Alamat tempat acara (bisa dikoreksi manual)..."
                                      class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">{{ old('lokasi_alamat', $berita?->lokasi_alamat) }}</textarea>
                            @error('lokasi_alamat')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="acara_mulai" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Mulai Acara</label>
                                <input type="datetime-local" id="acara_mulai" name="acara_mulai"
                                       value="{{ old('acara_mulai', $berita?->acara_mulai?->format('Y-m-d\TH:i')) }}"
                                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                @error('acara_mulai')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="acara_selesai" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Selesai Acara</label>
                                <input type="datetime-local" id="acara_selesai" name="acara_selesai"
                                       value="{{ old('acara_selesai', $berita?->acara_selesai?->format('Y-m-d\TH:i')) }}"
                                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                @error('acara_selesai')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-3.5 sm:p-4">
                            <div class="flex flex-wrap items-end gap-2">
                                <div class="min-w-[220px] flex-1">
                                    <label for="cari-lokasi" class="mb-1.5 block text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">
                                        Cari Alamat di Peta
                                    </label>
                                    <input type="text" id="cari-lokasi"
                                           placeholder="Contoh: Masjid Agung Ajibarang, Banyumas"
                                           class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-brand focus:ring-brand">
                                </div>
                                <button type="button" id="tombol-cari-lokasi"
                                        class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark disabled:cursor-wait disabled:opacity-60">
                                    Cari Alamat
                                </button>
                                <button type="button" id="tombol-lokasi-saya"
                                        class="rounded-xl border border-brand-border bg-white px-4 py-2.5 text-xs font-bold text-slate-600 transition hover:border-brand hover:text-brand">
                                    Gunakan Lokasi Saya
                                </button>
                                <button type="button" id="tombol-hapus-titik"
                                        class="rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-xs font-bold text-red-700 transition hover:bg-red-100">
                                    Hapus Titik
                                </button>
                            </div>

                            <p class="mt-2 text-[10.5px] leading-relaxed text-slate-500">
                                Klik peta atau geser marker untuk memilih titik acara. Pencarian memakai layanan publik
                                OpenStreetMap Nominatim; hasilnya bisa kurang tepat, jadi koreksi alamat dan titik secara manual.
                            </p>
                            <p id="status-peta" class="mt-1.5 text-[11px] font-semibold text-slate-500" role="status" aria-live="polite"></p>

                            <div id="peta-lokasi" class="mt-3 w-full rounded-2xl border border-slate-200 bg-slate-100"></div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="latitude" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Latitude</label>
                                <input type="number" id="latitude" name="latitude" step="any" min="-90" max="90"
                                       value="{{ old('latitude', $berita?->latitude) }}"
                                       placeholder="-7.4093000"
                                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                @error('latitude')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="longitude" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Longitude</label>
                                <input type="number" id="longitude" name="longitude" step="any" min="-180" max="180"
                                       value="{{ old('longitude', $berita?->longitude) }}"
                                       placeholder="109.0736000"
                                       class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                                @error('longitude')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="lokasi_keterangan" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Keterangan Lokasi</label>
                            <textarea id="lokasi_keterangan" name="lokasi_keterangan" rows="2" maxlength="1000"
                                      placeholder="Contoh: Masuk melalui gerbang timur, parkir di halaman belakang..."
                                      class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">{{ old('lokasi_keterangan', $berita?->lokasi_keterangan) }}</textarea>
                            @error('lokasi_keterangan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>
            </div>

            {{-- SIDEBAR --}}
            <aside class="space-y-6 xl:sticky xl:top-24">
                {{-- PUBLIKASI --}}
                <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-base font-black tracking-tight text-slate-900">Publikasi</h2>
                        <p class="mt-0.5 text-xs text-brand-muted">Tentukan status dan simpan berita.</p>
                    </div>
                    <div class="space-y-3 p-5">
                        @if ($berita?->isPublished())
                            <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-[11px] leading-relaxed text-amber-900">
                                <strong>Berita sudah terbit.</strong> Menyimpan sebagai draft akan menariknya dari halaman publik.
                                Jika diajukan review, revisi tidak tampil sampai disetujui super admin.
                            </div>
                        @elseif ($berita?->isPending())
                            <div class="rounded-xl border border-sky-200 bg-sky-50 px-3 py-2.5 text-[11px] leading-relaxed text-sky-900">
                                <strong>Menunggu review.</strong> Berita ini belum tampil publik. Perubahan yang disimpan tetap menunggu persetujuan super admin.
                            </div>
                        @elseif ($berita?->isRejected() && $berita->review_note)
                            <div class="rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-[11px] leading-relaxed text-red-900">
                                <strong>Ditolak:</strong> {{ $berita->review_note }}<br>
                                <span class="text-red-700">Perbaiki berita lalu ajukan review kembali.</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2.5">
                            <span class="text-xs font-semibold text-slate-500">Status saat ini</span>
                            <span id="status-preview"
                                  class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide {{ $kelasStatus[$statusSaatIni] ?? $kelasStatus[\App\Models\Berita::STATUS_DRAFT] }}">
                                {{ $labelStatus[$statusSaatIni] ?? 'Draft' }}
                            </span>
                        </div>

                        @error('status')<p class="text-xs font-semibold text-red-600">{{ $message }}</p>@enderror

                        @unless (auth()->user()?->isSuperAdmin())
                            <div class="rounded-xl border border-brand-border bg-brand-surface px-3 py-2.5 text-[11px] leading-relaxed text-brand-muted">
                                <strong class="text-brand">Perlu persetujuan super admin.</strong>
                                Admin biasa tidak dapat menerbitkan berita langsung. Ajukan review; berita baru tampil publik setelah disetujui super admin.
                            </div>
                        @endunless

                        <button type="submit" data-status="draft"
                                class="submit-berita flex w-full items-center justify-center gap-2 rounded-xl border border-brand bg-white px-4 py-3 text-sm font-extrabold text-brand transition hover:bg-emerald-50 disabled:cursor-wait disabled:opacity-60">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5h11l3 3v11H5V5Zm3 0v5h7V5M8 14h8"/>
                            </svg>
                            Simpan sebagai Draft
                        </button>

                        <button type="submit" data-status="pending"
                                class="submit-berita flex w-full items-center justify-center gap-2 rounded-xl bg-brand px-4 py-3 text-sm font-extrabold text-white shadow-sm transition hover:bg-brand-dark disabled:cursor-wait disabled:opacity-60">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 12 4 4L19 6"/>
                            </svg>
                            {{ $berita?->isPublished() ? 'Ajukan Revisi' : ($isEdit ? 'Simpan & Ajukan Review' : 'Ajukan Review') }}
                        </button>

                        <p id="indikator-simpan" class="hidden rounded-lg bg-sky-50 px-3 py-2 text-center text-[11px] font-semibold text-sky-800">
                            Menyimpan berita... Mohon tunggu.
                        </p>

                        <a href="{{ route('admin.berita.index') }}"
                           class="block rounded-xl border border-slate-200 px-4 py-2.5 text-center text-xs font-bold text-slate-600 transition hover:bg-slate-50">
                            Batal
                        </a>

                        <p class="text-[10.5px] leading-relaxed text-slate-400">
                            Hanya berita berstatus <strong>Terbit</strong> yang tampil di halaman publik.
                            Draft, menunggu review, dan ditolak tidak dapat diakses publik.
                        </p>
                    </div>
                </section>

                {{-- GAMBAR SAMPUL --}}
                <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-base font-black tracking-tight text-slate-900">Gambar Sampul</h2>
                        <p class="mt-0.5 text-xs text-brand-muted">Opsional. JPG, PNG, atau WebP maksimal 2 MB.</p>
                    </div>
                    <div class="space-y-3 p-5">
                        <div id="preview-kosong" class="{{ $gambarTersimpan ? 'hidden' : '' }} flex aspect-[16/10] flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 text-center">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-50 text-brand">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.5-4.5 3 3L15 11l5 5M5 20h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1Z"/>
                                </svg>
                            </span>
                            <p class="mt-2 text-xs font-bold text-slate-600">Belum ada gambar</p>
                            <p class="mt-1 text-[10.5px] text-slate-400">Pilih gambar dari perangkat Anda.</p>
                        </div>

                        <div id="preview-wrap" class="{{ $gambarTersimpan ? '' : 'hidden' }} overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                            <img id="preview-gambar"
                                 src="{{ $gambarTersimpan ? asset('storage/'.$gambarTersimpan) : '' }}"
                                 alt="Pratinjau gambar sampul"
                                 class="aspect-[16/10] w-full object-cover">
                        </div>

                        <label for="gambar"
                               class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-brand-border bg-white px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:border-brand hover:text-brand">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 16V4m0 0L8 8m4-4 4 4M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/>
                            </svg>
                            Pilih / Ganti Gambar
                        </label>
                        <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-xs file:font-bold file:text-emerald-800">

                        <p id="info-gambar" class="text-[10.5px] leading-relaxed text-slate-400"></p>
                        @error('gambar')<p class="text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>
                </section>

                {{-- GALERI FOTO --}}
                <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <h2 class="text-base font-black tracking-tight text-slate-900">Galeri Foto</h2>
                                <p class="mt-0.5 text-xs text-brand-muted">JPG, PNG, atau WebP maksimal {{ \App\Models\Berita::MAX_GALERI_SIZE_MB }} MB per foto.</p>
                            </div>
                            <span @class([
                                'rounded-full px-2.5 py-1 text-[10px] font-extrabold',
                                'bg-amber-50 text-amber-800' => $galeriPenuh,
                                'bg-emerald-50 text-emerald-800' => ! $galeriPenuh,
                            ])>
                                {{ $galeriTersimpan->count() }}/{{ \App\Models\Berita::MAX_GALERI }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        @if ($galeriMelebihiBatas)
                            <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-[11px] leading-relaxed text-amber-900">
                                <strong>Galeri ini memiliki {{ $galeriTersimpan->count() }} foto lama.</strong>
                                Data melebihi batas baru {{ \App\Models\Berita::MAX_GALERI }} foto tetap disimpan.
                                Halaman publik hanya menampilkan {{ \App\Models\Berita::MAX_GALERI }} foto pertama.
                                Hapus atau ganti foto untuk menyesuaikan batas.
                            </div>
                        @endif

                        @if ($isEdit && $galeriTersimpan->isNotEmpty())
                            <div class="grid grid-cols-2 gap-3">
                                @foreach ($galeriTersimpan as $foto)
                                    <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                        <a href="{{ route('berita.galeri.show', [$berita, $foto]) }}" target="_blank" rel="noopener"
                                           class="block aspect-[4/3] overflow-hidden" title="Buka foto ukuran penuh">
                                            <img src="{{ route('berita.galeri.show', [$berita, $foto]) }}"
                                                 alt="Foto galeri {{ $loop->iteration }}"
                                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                                 loading="lazy">
                                        </a>
                                        <button type="submit"
                                                form="hapus-galeri-{{ $foto->id }}"
                                                onclick="return confirm('Hapus foto galeri ini? Berita tidak akan terhapus.')"
                                                class="absolute right-1.5 top-1.5 flex h-7 w-7 items-center justify-center rounded-full bg-red-600/95 text-sm font-black text-white shadow-md transition hover:bg-red-700"
                                                title="Hapus foto galeri"
                                                aria-label="Hapus foto galeri {{ $loop->iteration }}">
                                            ×
                                        </button>
                                        <button type="button"
                                                data-pilih-ganti="ganti-file-{{ $foto->id }}"
                                                class="absolute inset-x-0 bottom-0 flex items-center justify-center gap-1 bg-slate-900/70 px-2 py-1.5 text-[10px] font-bold text-white backdrop-blur-sm transition hover:bg-slate-900/85"
                                                title="Ganti foto galeri"
                                                aria-label="Ganti foto galeri {{ $loop->iteration }}">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h3l2-2h6l2 2h3v12H4V7Zm8 3a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/>
                                            </svg>
                                            Ganti foto
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center">
                                <p class="text-xs font-bold text-slate-600">Belum ada foto galeri</p>
                                <p class="mt-1 text-[10.5px] text-slate-400">Tambahkan beberapa foto pendukung berita.</p>
                            </div>
                        @endif

                        @if (! $galeriPenuh)
                            <div>
                                <label for="galeri" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">
                                    Pilih Foto Galeri Sekaligus
                                </label>
                                <input type="file" id="galeri" name="galeri[]" multiple
                                       accept="image/jpeg,image/png,image/webp"
                                       title="Pilih hingga {{ \App\Models\Berita::MAX_GALERI_PER_UPLOAD }} foto sekaligus (Ctrl/Cmd + klik)"
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-xs file:font-bold file:text-emerald-800">
                                <p class="mt-1.5 text-[10.5px] leading-relaxed text-slate-400">
                                    Pilih hingga {{ \App\Models\Berita::MAX_GALERI_PER_UPLOAD }} foto dalam satu kali pilih
                                    (Ctrl/Cmd + klik), lalu simpan berita. Tidak perlu mengedit ulang untuk menambah foto.
                                    Maksimal {{ \App\Models\Berita::MAX_GALERI_SIZE_MB }} MB per foto dan total
                                    {{ \App\Models\Berita::MAX_GALERI }} foto per berita.
                                    Sisa slot: {{ max(0, \App\Models\Berita::MAX_GALERI - $galeriTersimpan->count()) }} foto.
                                </p>
                                <p id="info-galeri" class="mt-1 text-[10.5px] font-semibold text-slate-500" role="status" aria-live="polite"></p>
                            </div>

                            <div id="preview-galeri" class="hidden grid grid-cols-3 gap-2"></div>
                        @elseif (! $galeriMelebihiBatas)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[11px] leading-relaxed text-slate-600">
                                <strong>Galeri sudah penuh.</strong> Hapus atau ganti salah satu foto untuk mengubah galeri.
                            </div>
                        @endif

                        @foreach ($errors->getMessages() as $field => $messages)
                            @if ($field === 'foto' || $field === 'galeri' || str_starts_with($field, 'galeri.'))
                                @foreach ($messages as $message)
                                    <p class="text-xs font-semibold text-red-600">{{ $message }}</p>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </section>

                {{-- PENULIS & KATEGORI --}}
                <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <h2 class="text-base font-black tracking-tight text-slate-900">Penulis &amp; Kategori</h2>
                        <p class="mt-0.5 text-xs text-brand-muted">Atribut tambahan untuk publikasi.</p>
                    </div>
                    <div class="space-y-4 p-5">
                        <div>
                            <label for="penulis" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Penulis</label>
                            <input type="text" id="penulis" name="penulis" maxlength="255"
                                   value="{{ old('penulis', $berita?->penulis ?? auth()->user()?->name) }}"
                                   placeholder="Nama penulis"
                                   class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                            @error('penulis')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="kategori" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wider text-slate-600">Kategori</label>
                            <input type="text" id="kategori" name="kategori" maxlength="100" list="daftar-kategori"
                                   value="{{ $kategoriTersimpan }}"
                                   placeholder="Pilih atau tulis kategori"
                                   class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-brand focus:ring-brand">
                            <datalist id="daftar-kategori">
                                @foreach ($daftarKategori as $kategori)
                                    <option value="{{ $kategori }}"></option>
                                @endforeach
                            </datalist>
                            @error('kategori')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</form>

@if ($isEdit && $galeriTersimpan->isNotEmpty())
    {{-- Form terpisah agar penghapusan/penggantian satu foto tidak mengirim ulang form berita. --}}
    @foreach ($galeriTersimpan as $foto)
        <form id="hapus-galeri-{{ $foto->id }}"
              action="{{ route('admin.berita.galeri.destroy', [$berita, $foto]) }}"
              method="POST"
              class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <form id="ganti-galeri-{{ $foto->id }}"
              action="{{ route('admin.berita.galeri.replace', [$berita, $foto]) }}"
              method="POST"
              enctype="multipart/form-data"
              class="hidden">
            @csrf
            @method('PATCH')
            <input type="file"
                   id="ganti-file-{{ $foto->id }}"
                   name="foto"
                   accept="image/jpeg,image/png,image/webp"
                   data-ganti-galeri>
        </form>
    @endforeach
@endif

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-berita');
            const judul = document.getElementById('judul');
            const slug = document.getElementById('slug');
            const hitungJudul = document.getElementById('hitung-judul');
            const ringkasan = document.getElementById('ringkasan');
            const hitungRingkasan = document.getElementById('hitung-ringkasan');
            const statusInput = document.getElementById('status-berita');
            const indikator = document.getElementById('indikator-simpan');
            const tombolSubmit = document.querySelectorAll('.submit-berita');

            const buatSlug = (teks) => teks
                .toString()
                .toLowerCase()
                .normalize('NFKD')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');

            let slugManual = slug && slug.value.trim() !== '';

            if (slug) {
                slug.addEventListener('input', () => {
                    slugManual = slug.value.trim() !== '';
                });
            }

            if (judul && slug) {
                judul.addEventListener('input', () => {
                    if (!slugManual) {
                        slug.value = buatSlug(judul.value);
                    }

                    if (hitungJudul) {
                        hitungJudul.textContent = judul.value.length;
                    }
                });
            }

            if (ringkasan && hitungRingkasan) {
                ringkasan.addEventListener('input', () => {
                    hitungRingkasan.textContent = ringkasan.value.length;
                });
            }

            form?.querySelectorAll('[data-status]').forEach((tombol) => {
                tombol.addEventListener('click', () => {
                    if (!statusInput) return;

                    statusInput.value = tombol.dataset.status;
                });
            });

            if (form) {
                form.addEventListener('submit', (event) => {
                    if (form.dataset.submitting === 'true') {
                        event.preventDefault();
                        return;
                    }

                    form.dataset.submitting = 'true';

                    tombolSubmit.forEach((tombol) => {
                        tombol.disabled = true;
                        tombol.classList.add('opacity-60', 'cursor-wait');
                    });

                    if (indikator) {
                        indikator.classList.remove('hidden');
                    }
                });
            }

            // Pratinjau gambar sampul.
            const inputGambar = document.getElementById('gambar');
            const previewWrap = document.getElementById('preview-wrap');
            const previewKosong = document.getElementById('preview-kosong');
            const previewGambar = document.getElementById('preview-gambar');
            const infoGambar = document.getElementById('info-gambar');

            if (inputGambar) {
                inputGambar.addEventListener('change', () => {
                    const file = inputGambar.files?.[0];

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
                        if (infoGambar) {
                            infoGambar.textContent = pesan.join(' ') + ' Pilih berkas lain.';
                            infoGambar.className = 'text-[10.5px] font-semibold leading-relaxed text-red-600';
                        }
                        return;
                    }

                    const reader = new FileReader();

                    reader.addEventListener('load', () => {
                        if (previewGambar) previewGambar.src = reader.result;
                        if (previewWrap) previewWrap.classList.remove('hidden');
                        if (previewKosong) previewKosong.classList.add('hidden');
                        if (infoGambar) {
                            infoGambar.textContent = file.name + ' · ' + (file.size / 1024).toFixed(0) + ' KB';
                            infoGambar.className = 'text-[10.5px] leading-relaxed text-emerald-700';
                        }
                    });

                    reader.readAsDataURL(file);
                });
            }

            // Pratinjau beberapa foto galeri sebelum diunggah.
            const inputGaleri = document.getElementById('galeri');
            const infoGaleri = document.getElementById('info-galeri');
            const previewGaleri = document.getElementById('preview-galeri');
            const galeriTersimpan = {{ $galeriTersimpan->count() }};
            const maxGaleri = {{ \App\Models\Berita::MAX_GALERI }};
            const maxUploadGaleri = {{ \App\Models\Berita::MAX_GALERI_PER_UPLOAD }};
            const maxGaleriBytes = {{ \App\Models\Berita::MAX_GALERI_SIZE_MB }} * 1024 * 1024;
            const tipeGaleriDiizinkan = ['image/jpeg', 'image/png', 'image/webp'];

            if (inputGaleri) {
                inputGaleri.addEventListener('change', () => {
                    const files = Array.from(inputGaleri.files || []);
                    const pesan = [];
                    const sisaSlot = Math.max(0, maxGaleri - galeriTersimpan);

                    if (files.length > maxUploadGaleri) {
                        pesan.push('Maksimal ' + maxUploadGaleri + ' foto per unggahan.');
                    }

                    if (files.length > sisaSlot) {
                        pesan.push('Sisa kuota galeri hanya ' + sisaSlot + ' foto.');
                    }

                    files.forEach((file) => {
                        if (!tipeGaleriDiizinkan.includes(file.type)) {
                            pesan.push(file.name + ': tipe berkas tidak didukung.');
                        }

                        if (file.size > maxGaleriBytes) {
                            pesan.push(file.name + ': ukuran melebihi {{ \App\Models\Berita::MAX_GALERI_SIZE_MB }} MB.');
                        }
                    });

                    if (previewGaleri) {
                        previewGaleri.innerHTML = '';
                        previewGaleri.classList.toggle('hidden', files.length === 0);

                        files.forEach((file) => {
                            if (!tipeGaleriDiizinkan.includes(file.type)) {
                                return;
                            }

                            const gambar = document.createElement('img');
                            gambar.src = URL.createObjectURL(file);
                            gambar.alt = 'Pratinjau ' + file.name;
                            gambar.className = 'aspect-[4/3] w-full rounded-lg border border-slate-200 object-cover';
                            previewGaleri.appendChild(gambar);
                        });
                    }

                    if (infoGaleri) {
                        if (pesan.length > 0) {
                            infoGaleri.textContent = pesan.join(' ');
                            infoGaleri.className = 'mt-1 text-[10.5px] font-semibold leading-relaxed text-red-600';
                        } else if (files.length > 0) {
                            infoGaleri.textContent = files.length + ' foto dipilih. Total setelah disimpan: '
                                + (galeriTersimpan + files.length) + ' dari ' + maxGaleri + ' foto.';
                            infoGaleri.className = 'mt-1 text-[10.5px] font-semibold leading-relaxed text-emerald-700';
                        } else {
                            infoGaleri.textContent = '';
                            infoGaleri.className = 'mt-1 text-[10.5px] font-semibold text-slate-500';
                        }
                    }
                });
            }

            // Ganti satu foto galeri tanpa menambah jumlah foto.
            document.querySelectorAll('[data-pilih-ganti]').forEach((tombol) => {
                tombol.addEventListener('click', () => {
                    document.getElementById(tombol.dataset.pilihGanti)?.click();
                });
            });

            document.querySelectorAll('[data-ganti-galeri]').forEach((input) => {
                input.addEventListener('change', () => {
                    const file = input.files?.[0];

                    if (!file) {
                        return;
                    }

                    if (!tipeGaleriDiizinkan.includes(file.type)) {
                        window.alert('Format foto pengganti harus JPG, JPEG, PNG, atau WebP.');
                        input.value = '';
                        return;
                    }

                    if (file.size > maxGaleriBytes) {
                        window.alert('Ukuran foto pengganti maksimal {{ \App\Models\Berita::MAX_GALERI_SIZE_MB }} MB.');
                        input.value = '';
                        return;
                    }

                    if (!window.confirm('Ganti foto galeri ini? Foto lama akan dihapus.')) {
                        input.value = '';
                        return;
                    }

                    input.closest('form')?.submit();
                });
            });

            // Peta lokasi acara.
            const petaEl = document.getElementById('peta-lokasi');

            if (!petaEl || typeof L === 'undefined') {
                return;
            }

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const cariInput = document.getElementById('cari-lokasi');
            const alamatInput = document.getElementById('lokasi_alamat');
            const lokasiNamaInput = document.getElementById('lokasi_nama');
            const tombolCari = document.getElementById('tombol-cari-lokasi');
            const tombolLokasiSaya = document.getElementById('tombol-lokasi-saya');
            const tombolHapusTitik = document.getElementById('tombol-hapus-titik');
            const statusPeta = document.getElementById('status-peta');
            const titikAwal = [-7.4093, 109.0736];

            const peta = L.map(petaEl, { scrollWheelZoom: false }).setView(titikAwal, 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
            }).addTo(peta);

            let marker = null;
            let sinkronInput = false;

            const aturStatus = (pesan, error = false) => {
                if (!statusPeta) return;
                statusPeta.textContent = pesan;
                statusPeta.className = 'mt-1.5 text-[11px] font-semibold ' + (error ? 'text-red-600' : 'text-slate-500');
            };

            const aturMarker = (lat, lng, opsi = {}) => {
                const { pan = true, isiInput = true } = opsi;

                if (!Number.isFinite(lat) || !Number.isFinite(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                    aturStatus('Koordinat tidak valid. Latitude harus -90 sampai 90 dan longitude -180 sampai 180.', true);
                    return;
                }

                if (!marker) {
                    marker = L.marker([lat, lng], { draggable: true }).addTo(peta);
                    marker.on('dragend', () => {
                        const posisi = marker.getLatLng();
                        aturMarker(posisi.lat, posisi.lng, { pan: false });
                    });
                } else {
                    marker.setLatLng([lat, lng]);
                }

                if (pan) {
                    peta.setView([lat, lng], Math.max(peta.getZoom(), 14));
                }

                if (isiInput) {
                    sinkronInput = true;
                    if (latInput) latInput.value = lat.toFixed(7);
                    if (lngInput) lngInput.value = lng.toFixed(7);
                    sinkronInput = false;
                }

                aturStatus('Titik lokasi dipilih. Geser marker atau koreksi koordinat bila perlu.');
            };

            peta.on('click', (event) => {
                aturMarker(event.latlng.lat, event.latlng.lng);
            });

            const latAwal = parseFloat(latInput?.value);
            const lngAwal = parseFloat(lngInput?.value);

            if (Number.isFinite(latAwal) && Number.isFinite(lngAwal)) {
                aturMarker(latAwal, lngAwal, { pan: true, isiInput: false });
            }

            [latInput, lngInput].forEach((input) => {
                input?.addEventListener('change', () => {
                    if (sinkronInput) return;

                    const lat = parseFloat(latInput?.value);
                    const lng = parseFloat(lngInput?.value);

                    if (Number.isFinite(lat) && Number.isFinite(lng)) {
                        aturMarker(lat, lng, { isiInput: false });
                    } else if ((latInput?.value || '').trim() !== '' || (lngInput?.value || '').trim() !== '') {
                        aturStatus('Isi latitude dan longitude bersama-sama agar titik dapat ditampilkan.', true);
                    }
                });
            });

            tombolCari?.addEventListener('click', async () => {
                const kataKunci = (cariInput?.value || '').trim();

                if (kataKunci.length < 3) {
                    aturStatus('Masukkan minimal 3 karakter alamat yang ingin dicari.', true);
                    return;
                }

                tombolCari.disabled = true;
                aturStatus('Mencari alamat pada layanan OpenStreetMap...');

                try {
                    const url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=' + encodeURIComponent(kataKunci);
                    const response = await fetch(url, { headers: { Accept: 'application/json' } });

                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }

                    const hasil = await response.json();

                    if (!Array.isArray(hasil) || hasil.length === 0) {
                        aturStatus('Alamat tidak ditemukan. Coba kata kunci lain atau tandai titik secara manual di peta.', true);
                        return;
                    }

                    const lokasi = hasil[0];
                    const lat = parseFloat(lokasi.lat);
                    const lng = parseFloat(lokasi.lon);

                    if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
                        aturStatus('Hasil pencarian tidak memiliki koordinat yang valid.', true);
                        return;
                    }

                    aturMarker(lat, lng);

                    if (alamatInput && alamatInput.value.trim() === '' && lokasi.display_name) {
                        alamatInput.value = lokasi.display_name;
                    }

                    if (lokasiNamaInput && lokasiNamaInput.value.trim() === '' && lokasi.name) {
                        lokasiNamaInput.value = lokasi.name;
                    }

                    aturStatus('Alamat ditemukan. Periksa kembali titik dan alamat karena hasil geocoding bisa kurang akurat.');
                } catch (error) {
                    aturStatus('Pencarian alamat gagal. Periksa koneksi internet atau tandai titik secara manual.', true);
                } finally {
                    tombolCari.disabled = false;
                }
            });

            cariInput?.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    tombolCari?.click();
                }
            });

            tombolLokasiSaya?.addEventListener('click', () => {
                if (!navigator.geolocation) {
                    aturStatus('Perangkat ini tidak mendukung geolokasi. Silakan tandai titik secara manual.', true);
                    return;
                }

                aturStatus('Meminta izin lokasi perangkat...');
                tombolLokasiSaya.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    (posisi) => {
                        aturMarker(posisi.coords.latitude, posisi.coords.longitude);
                        aturStatus('Titik diisi dari lokasi perangkat. Pastikan titik tersebut memang lokasi acara.');
                        tombolLokasiSaya.disabled = false;
                    },
                    () => {
                        aturStatus('Lokasi perangkat tidak dapat diambil. Izinkan akses lokasi atau tandai titik secara manual.', true);
                        tombolLokasiSaya.disabled = false;
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
                );
            });

            tombolHapusTitik?.addEventListener('click', () => {
                if (marker) {
                    peta.removeLayer(marker);
                    marker = null;
                }

                if (latInput) latInput.value = '';
                if (lngInput) lngInput.value = '';
                aturStatus('Titik peta dihapus. Simpan tanpa koordinat jika berita tidak memerlukan lokasi.');
            });
        });
    </script>
@endpush

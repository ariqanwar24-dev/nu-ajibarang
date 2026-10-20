<x-admin-layout title="Kelola Anggota">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-brand">Verifikasi Keanggotaan</p>
                <h1 class="mt-1 font-serif text-2xl font-semibold tracking-tight text-brand-deeper sm:text-3xl">Kelola Anggota</h1>
                <p class="mt-1.5 text-sm text-brand-muted">Tinjau data anggota, setujui pengajuan, atau tolak dengan catatan yang jelas.</p>
            </div>
            <a href="{{ route('admin.anggota.create') }}"
               class="inline-flex w-fit items-center gap-2 rounded-xl bg-brand px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>
                </svg>
                Tambah Anggota
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-[1500px] space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        {{-- Ringkasan --}}
        <section class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-amber-200 bg-amber-50/70 p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-amber-800">Menunggu Verifikasi</p>
                <p class="mt-1 text-3xl font-black text-amber-900">{{ $ringkasanStatus['pending'] }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-emerald-800">Disetujui</p>
                <p class="mt-1 text-3xl font-black text-emerald-900">{{ $ringkasanStatus['approved'] }}</p>
            </div>
            <div class="rounded-2xl border border-red-200 bg-red-50/70 p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-red-800">Ditolak</p>
                <p class="mt-1 text-3xl font-black text-red-900">{{ $ringkasanStatus['rejected'] }}</p>
            </div>
        </section>

        {{-- Filter --}}
        <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filter status anggota">
            @php
                $filterAwal = [
                    'semua' => ['label' => 'Semua', 'jumlah' => $anggotas->count()],
                    'pending' => ['label' => 'Menunggu', 'jumlah' => $ringkasanStatus['pending']],
                    'approved' => ['label' => 'Disetujui', 'jumlah' => $ringkasanStatus['approved']],
                    'rejected' => ['label' => 'Ditolak', 'jumlah' => $ringkasanStatus['rejected']],
                ];
            @endphp

            @foreach ($filterAwal as $key => $filter)
                <button type="button" data-filter-anggota="{{ $key }}"
                        class="filter-anggota rounded-full px-4 py-2 text-xs font-bold transition {{ $key === 'semua' ? 'bg-brand text-white shadow-sm' : 'border border-brand-border bg-white text-slate-600 hover:border-brand hover:text-brand' }}">
                    {{ $filter['label'] }} ({{ $filter['jumlah'] }})
                </button>
            @endforeach
        </div>

        <div id="anggota-kosong-filter" class="hidden rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center">
            <p class="text-sm font-bold text-slate-700">Tidak ada anggota dengan status tersebut.</p>
            <p class="mt-1 text-xs text-slate-500">Pilih filter “Semua” untuk melihat seluruh data.</p>
        </div>

        @if ($anggotas->isNotEmpty())
            <div class="grid gap-4 xl:grid-cols-2">
                @foreach ($anggotas as $anggota)
                    @php
                        $status = match ($anggota->status_verifikasi) {
                            'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-800', 'border' => 'border-emerald-200'],
                            'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700', 'border' => 'border-red-200'],
                            default => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-800', 'border' => 'border-amber-200'],
                        };
                    @endphp

                    <article class="kartu-anggota overflow-hidden rounded-3xl border {{ $status['border'] }} bg-white shadow-sm"
                             data-status="{{ $anggota->status_verifikasi }}">
                        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 px-5 py-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="h-14 w-14 shrink-0 overflow-hidden rounded-2xl bg-emerald-50">
                                    @if ($anggota->foto)
                                        <img src="{{ asset('storage/'.$anggota->foto) }}" alt="Foto {{ $anggota->nama_anggota }}"
                                             class="h-full w-full object-cover" loading="lazy">
                                    @else
                                        <span class="flex h-full w-full items-center justify-center text-lg font-black text-emerald-800">
                                            {{ mb_substr($anggota->nama_anggota, 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h2 class="truncate text-base font-black text-slate-900">{{ $anggota->nama_anggota }}</h2>
                                    <p class="mt-0.5 truncate text-xs text-slate-500">{{ $anggota->user?->email ?? 'Akun tidak tersedia' }}</p>
                                    @if ($anggota->nomor_anggota)
                                        <p class="mt-1 inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[9.5px] font-bold text-slate-600">
                                            No. {{ $anggota->nomor_anggota }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <span class="shrink-0 rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>
                        </div>

                        <div class="grid gap-x-5 gap-y-3 p-5 sm:grid-cols-2">
                            <div>
                                <p class="text-[9.5px] font-extrabold uppercase tracking-wider text-slate-400">Jenis Kelamin</p>
                                <p class="mt-0.5 text-xs font-semibold text-slate-700">
                                    {{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[9.5px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Lahir</p>
                                <p class="mt-0.5 text-xs font-semibold text-slate-700">
                                    {{ $anggota->tanggal_lahir?->format('d-m-Y') ?? 'Belum diisi' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[9.5px] font-extrabold uppercase tracking-wider text-slate-400">Nomor HP</p>
                                <p class="mt-0.5 text-xs font-semibold text-slate-700">{{ $anggota->no_hp }}</p>
                            </div>
                            <div>
                                <p class="text-[9.5px] font-extrabold uppercase tracking-wider text-slate-400">Tanggal Pengajuan</p>
                                <p class="mt-0.5 text-xs font-semibold text-slate-700">{{ $anggota->created_at?->format('d-m-Y H:i') ?? '—' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-[9.5px] font-extrabold uppercase tracking-wider text-slate-400">Alamat</p>
                                <p class="mt-0.5 whitespace-pre-line text-xs leading-relaxed text-slate-700">{{ $anggota->alamat }}</p>
                            </div>

                            @if ($anggota->status_verifikasi === 'rejected' && $anggota->catatan_verifikasi)
                                <div class="sm:col-span-2 rounded-xl border border-red-100 bg-red-50 px-3 py-2">
                                    <p class="text-[9.5px] font-extrabold uppercase tracking-wider text-red-500">Catatan Penolakan</p>
                                    <p class="mt-0.5 whitespace-pre-line text-xs leading-relaxed text-red-800">{{ $anggota->catatan_verifikasi }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2 border-t border-slate-100 bg-slate-50/60 px-5 py-4">
                            <form action="{{ route('admin.anggota.verifikasi', $anggota) }}" method="POST" class="inline-flex">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status_verifikasi" value="approved">
                                <button type="submit"
                                        class="rounded-xl bg-emerald-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-800">
                                    Setujui Anggota
                                </button>
                            </form>

                            <button type="button" data-toggle-tolak="tolak-{{ $anggota->id }}"
                                    class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-xs font-bold text-red-700 transition hover:bg-red-100">
                                Tolak
                            </button>

                            <form action="{{ route('admin.anggota.destroy', $anggota) }}" method="POST"
                                  onsubmit="return confirm('Hapus data anggota ini? Tindakan ini tidak dapat dibatalkan.')"
                                  class="ml-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-500 transition hover:border-slate-300 hover:text-slate-700">
                                    Hapus
                                </button>
                            </form>
                        </div>

                        <div id="tolak-{{ $anggota->id }}" class="hidden border-t border-red-100 bg-red-50/40 px-5 py-4">
                            <form action="{{ route('admin.anggota.verifikasi', $anggota) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status_verifikasi" value="rejected">

                                <label for="catatan-{{ $anggota->id }}" class="mb-1.5 block text-[10.5px] font-extrabold uppercase tracking-wider text-red-700">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea id="catatan-{{ $anggota->id }}" name="catatan_verifikasi" rows="2" required maxlength="1000"
                                          placeholder="Jelaskan data yang perlu diperbaiki..."
                                          class="w-full rounded-xl border-red-200 text-xs leading-relaxed shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('catatan_verifikasi') }}</textarea>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <button type="submit"
                                            class="rounded-xl bg-red-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-red-800">
                                        Kirim Penolakan
                                    </button>
                                    <button type="button" data-toggle-tolak="tolak-{{ $anggota->id }}"
                                            class="rounded-xl border border-red-200 bg-white px-4 py-2 text-xs font-bold text-red-700 hover:bg-red-50">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <section class="rounded-3xl border border-brand-border bg-white px-6 py-16 text-center shadow-sm">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-brand">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857a5.002 5.002 0 0 0-9.288 0M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </div>
                <h2 class="mt-4 text-lg font-black text-slate-800">Belum ada data anggota</h2>
                <p class="mt-1 text-sm text-slate-500">Pengajuan anggota atau data yang Anda tambahkan akan muncul di sini.</p>
                <a href="{{ route('admin.anggota.create') }}"
                   class="mt-5 inline-flex items-center gap-2 rounded-xl bg-brand px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-dark">
                    + Tambah Anggota
                </a>
            </section>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tombolFilter = document.querySelectorAll('.filter-anggota');
                const kartu = document.querySelectorAll('.kartu-anggota');
                const pesanKosong = document.getElementById('anggota-kosong-filter');

                const aktifkan = (tombol) => {
                    tombolFilter.forEach((item) => {
                        item.classList.remove('bg-brand', 'text-white', 'shadow-sm');
                        item.classList.add('border', 'border-brand-border', 'bg-white', 'text-slate-600');
                    });
                    tombol.classList.remove('border', 'border-brand-border', 'bg-white', 'text-slate-600');
                    tombol.classList.add('bg-brand', 'text-white', 'shadow-sm');
                };

                tombolFilter.forEach((tombol) => {
                    tombol.addEventListener('click', () => {
                        const filter = tombol.dataset.filterAnggota;
                        let tampil = 0;

                        kartu.forEach((item) => {
                            const cocok = filter === 'semua' || item.dataset.status === filter;
                            item.classList.toggle('hidden', !cocok);
                            if (cocok) tampil++;
                        });

                        if (pesanKosong) {
                            pesanKosong.classList.toggle('hidden', tampil > 0 || kartu.length === 0);
                        }

                        aktifkan(tombol);
                    });
                });

                document.querySelectorAll('[data-toggle-tolak]').forEach((tombol) => {
                    tombol.addEventListener('click', () => {
                        const target = document.getElementById(tombol.dataset.toggleTolak);
                        target?.classList.toggle('hidden');

                        if (target && !target.classList.contains('hidden')) {
                            target.querySelector('textarea')?.focus();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-admin-layout>

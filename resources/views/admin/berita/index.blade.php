<x-admin-layout title="Kelola Berita">
    @php
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
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-brand">Pusat Publikasi</p>
                <h1 class="mt-1 font-serif text-2xl font-semibold tracking-tight text-brand-deeper sm:text-3xl">Kelola Berita</h1>
                <p class="mt-1.5 text-sm text-brand-muted">
                    @if (auth()->user()->isSuperAdmin())
                        Kelola seluruh berita, tinjau pengajuan, dan tentukan publikasi.
                    @else
                        Kelola berita milik Anda, simpan draft, lalu ajukan review kepada super admin.
                    @endif
                </p>
            </div>
            <a href="{{ route('admin.berita.create') }}"
               class="inline-flex w-fit items-center gap-2 rounded-xl bg-brand px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>
                </svg>
                Tulis Berita
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-[1500px] space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        {{-- Ringkasan status --}}
        <section class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-5">
            <div class="rounded-2xl border border-brand-border bg-white p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">Total Berita</p>
                <p class="mt-1 text-3xl font-black text-slate-900">{{ $ringkasanStatus['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-brand-border bg-white p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">Draft</p>
                <p class="mt-1 text-3xl font-black text-slate-700">{{ $ringkasanStatus['draft'] }}</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-amber-50/60 p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-amber-700">Menunggu Review</p>
                <p class="mt-1 text-3xl font-black text-amber-800">{{ $ringkasanStatus['pending'] }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-emerald-700">Terbit</p>
                <p class="mt-1 text-3xl font-black text-emerald-800">{{ $ringkasanStatus['published'] }}</p>
            </div>
            <div class="rounded-2xl border border-red-100 bg-red-50/60 p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-red-700">Ditolak</p>
                <p class="mt-1 text-3xl font-black text-red-800">{{ $ringkasanStatus['rejected'] }}</p>
            </div>
        </section>

        {{-- Panel review khusus super admin --}}
        @if (auth()->user()->isSuperAdmin())
            <section class="overflow-hidden rounded-3xl border border-amber-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-amber-100 bg-amber-50/70 px-5 py-4 sm:px-6">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-amber-700">Persetujuan Publikasi</p>
                        <h2 class="mt-0.5 text-lg font-black tracking-tight text-slate-900">Berita Menunggu Review</h2>
                        <p class="mt-0.5 text-xs text-amber-900/70">Baca pratinjau sebelum menyetujui atau menolak berita.</p>
                    </div>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-extrabold text-amber-900">
                        {{ $beritaMenunggu->count() }} menunggu
                    </span>
                </div>

                @if ($beritaMenunggu->isNotEmpty())
                    <div class="divide-y divide-slate-100">
                        @foreach ($beritaMenunggu as $item)
                            <article x-data="{ rejectOpen: false }" class="p-5 sm:p-6">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div class="flex min-w-0 gap-3.5">
                                        <div class="h-16 w-24 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                            @if ($item->gambar)
                                                <img src="{{ asset('storage/'.$item->gambar) }}" alt="Sampul {{ $item->judul }}"
                                                     class="h-full w-full object-cover" loading="lazy">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-[9px] font-bold uppercase tracking-wide text-slate-400">
                                                    Tanpa foto
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[9.5px] font-extrabold uppercase tracking-wide text-amber-800">
                                                    Menunggu Review
                                                </span>
                                                @if ($item->kategori)
                                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[9.5px] font-bold text-slate-600">{{ $item->kategori }}</span>
                                                @endif
                                            </div>
                                            <h3 class="mt-1.5 text-base font-black text-slate-900">{{ $item->judul }}</h3>
                                            <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-500">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($item->ringkasan ?: $item->isi), 180) }}
                                            </p>
                                            <p class="mt-1.5 text-[10.5px] text-slate-400">
                                                {{ $item->penulis ?: ($item->user?->name ?? 'Redaksi') }}
                                                · diajukan {{ $item->submitted_at?->diffForHumans() ?? $item->created_at?->diffForHumans() ?? '—' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                                        <a href="{{ route('berita.show', $item) }}" target="_blank" rel="noopener"
                                           class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 transition hover:border-brand hover:text-brand">
                                            Baca Pratinjau ↗
                                        </a>
                                        <form action="{{ route('admin.berita.review', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit"
                                                    class="rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white transition hover:bg-emerald-700">
                                                Setujui &amp; Terbitkan
                                            </button>
                                        </form>
                                        <button type="button" @click="rejectOpen = ! rejectOpen"
                                                class="rounded-xl border border-red-200 bg-red-50 px-3.5 py-2 text-xs font-bold text-red-700 transition hover:bg-red-100">
                                            Tolak
                                        </button>
                                    </div>
                                </div>

                                <div x-show="rejectOpen" x-cloak class="mt-4 rounded-2xl border border-red-100 bg-red-50/50 p-4">
                                    <form action="{{ route('admin.berita.review', $item) }}" method="POST" class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="action" value="reject">
                                        <div>
                                            <label for="review-note-{{ $item->id }}" class="mb-1.5 block text-[10.5px] font-extrabold uppercase tracking-wider text-red-800">
                                                Alasan Penolakan <span class="text-red-600">*</span>
                                            </label>
                                            <textarea id="review-note-{{ $item->id }}" name="review_note" rows="2" required maxlength="1000"
                                                      placeholder="Jelaskan bagian yang perlu diperbaiki..."
                                                      class="w-full rounded-xl border-red-200 bg-white text-sm shadow-sm focus:border-red-400 focus:ring-red-300"></textarea>
                                            @error('review_note')
                                                <p class="mt-1 text-xs font-semibold text-red-700">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="submit"
                                                    class="rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-red-700">
                                                Kirim Penolakan
                                            </button>
                                            <button type="button" @click="rejectOpen = false"
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
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm font-bold text-slate-700">Tidak ada berita yang menunggu review.</p>
                        <p class="mt-1 text-xs text-slate-500">Pengajuan baru dari admin akan muncul di sini.</p>
                    </div>
                @endif
            </section>
        @endif

        {{-- Filter status --}}
        <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filter status berita">
            <button type="button" data-filter="semua"
                    class="filter-berita rounded-full bg-brand px-4 py-2 text-xs font-bold text-white shadow-sm">
                Semua ({{ $ringkasanStatus['total'] }})
            </button>
            <button type="button" data-filter="draft"
                    class="filter-berita rounded-full border border-brand-border bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:border-brand hover:text-brand">
                Draft ({{ $ringkasanStatus['draft'] }})
            </button>
            <button type="button" data-filter="pending"
                    class="filter-berita rounded-full border border-brand-border bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:border-brand hover:text-brand">
                Menunggu Review ({{ $ringkasanStatus['pending'] }})
            </button>
            <button type="button" data-filter="published"
                    class="filter-berita rounded-full border border-brand-border bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:border-brand hover:text-brand">
                Terbit ({{ $ringkasanStatus['published'] }})
            </button>
            <button type="button" data-filter="rejected"
                    class="filter-berita rounded-full border border-brand-border bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:border-brand hover:text-brand">
                Ditolak ({{ $ringkasanStatus['rejected'] }})
            </button>
        </div>

        <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
            @if ($berita->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left text-sm">
                        <thead class="border-b border-slate-100 bg-slate-50/80 text-[10.5px] uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3.5 font-extrabold">Berita</th>
                                <th class="px-4 py-3.5 font-extrabold">Penulis</th>
                                <th class="px-4 py-3.5 font-extrabold">Status</th>
                                <th class="px-4 py-3.5 font-extrabold">Tanggal</th>
                                <th class="px-5 py-3.5 text-right font-extrabold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($berita as $item)
                                <tr class="berita-row transition hover:bg-slate-50/70" data-status="{{ $item->status }}">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-16 w-24 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                                @if ($item->gambar)
                                                    <img src="{{ asset('storage/'.$item->gambar) }}" alt="Sampul {{ $item->judul }}"
                                                         class="h-full w-full object-cover" loading="lazy">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-[9px] font-bold uppercase tracking-wide text-slate-400">
                                                        Tanpa foto
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 max-w-md">
                                                <p class="truncate font-bold text-slate-800">{{ $item->judul }}</p>
                                                <p class="mt-1 line-clamp-2 text-[11.5px] leading-relaxed text-slate-500">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($item->ringkasan ?: $item->isi), 110) }}
                                                </p>
                                                @if ($item->isRejected() && $item->review_note)
                                                    <p class="mt-1.5 line-clamp-2 rounded-lg bg-red-50 px-2 py-1 text-[10.5px] font-semibold text-red-700">
                                                        Alasan ditolak: {{ $item->review_note }}
                                                    </p>
                                                @endif
                                                <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                                    @if ($item->kategori)
                                                        <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[9.5px] font-bold text-amber-800">{{ $item->kategori }}</span>
                                                    @endif
                                                    @if ($item->memilikiKoordinat())
                                                        <span class="rounded-full bg-sky-50 px-2 py-0.5 text-[9.5px] font-bold text-sky-700">Lokasi peta</span>
                                                    @endif
                                                    @if ($item->memilikiLokasi())
                                                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[9.5px] font-bold text-slate-600">Lokasi acara</span>
                                                    @endif
                                                    @if (($item->galeri_count ?? 0) > 0)
                                                        <span class="rounded-full bg-violet-50 px-2 py-0.5 text-[9.5px] font-bold text-violet-700">
                                                            {{ $item->galeri_count }} foto galeri
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <p class="text-xs font-semibold text-slate-700">{{ $item->penulis ?: ($item->user?->name ?? 'Redaksi') }}</p>
                                        <p class="mt-0.5 text-[10.5px] text-slate-400">{{ $item->user?->email ?? '—' }}</p>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide',
                                            $kelasStatus[$item->status] ?? $kelasStatus[\App\Models\Berita::STATUS_DRAFT],
                                        ])>
                                            {{ $labelStatus[$item->status] ?? 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-xs text-slate-500">
                                        <p>{{ $item->published_at?->format('d M Y') ?? $item->created_at?->format('d M Y') ?? '—' }}</p>
                                        @if ($item->submitted_at)
                                            <p class="mt-0.5 text-[10px] text-slate-400">Diajukan {{ $item->submitted_at->diffForHumans() }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap justify-end gap-1.5">
                                            @can('preview', $item)
                                                <a href="{{ route('berita.show', $item) }}" target="_blank" rel="noopener"
                                                   class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-bold text-slate-600 hover:border-brand hover:text-brand">
                                                    Lihat
                                                </a>
                                            @endcan
                                            @can('update', $item)
                                                <a href="{{ route('admin.berita.edit', $item) }}"
                                                   class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-[11px] font-bold text-amber-900 hover:bg-amber-200">
                                                    Edit
                                                </a>
                                            @endcan
                                            @can('delete', $item)
                                                <form action="{{ route('admin.berita.destroy', $item) }}" method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-lg bg-red-100 px-2.5 py-1.5 text-[11px] font-bold text-red-700 hover:bg-red-200">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="berita-kosong-filter" class="hidden px-6 py-12 text-center">
                    <p class="text-sm font-bold text-slate-700">Tidak ada berita dengan status tersebut.</p>
                    <p class="mt-1 text-xs text-slate-500">Pilih filter “Semua” untuk melihat seluruh berita.</p>
                </div>
            @else
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-brand">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2M7 16h6M7 8h6v4H7V8Z"/>
                        </svg>
                    </div>
                    <h2 class="mt-4 text-lg font-black text-slate-800">Belum ada berita</h2>
                    <p class="mt-1 text-sm text-slate-500">Berita yang Anda simpan akan muncul di sini.</p>
                    <a href="{{ route('admin.berita.create') }}"
                       class="mt-5 inline-flex items-center gap-2 rounded-xl bg-brand px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-dark">
                        + Tulis Berita Pertama
                    </a>
                </div>
            @endif
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tombolFilter = document.querySelectorAll('.filter-berita');
                const baris = document.querySelectorAll('.berita-row');
                const pesanKosong = document.getElementById('berita-kosong-filter');

                const aktifkanTombol = (tombol) => {
                    tombolFilter.forEach((item) => {
                        item.classList.remove('bg-brand', 'text-white', 'shadow-sm');
                        item.classList.add('border', 'border-brand-border', 'bg-white', 'text-slate-600');
                    });
                    tombol.classList.remove('border', 'border-brand-border', 'bg-white', 'text-slate-600');
                    tombol.classList.add('bg-brand', 'text-white', 'shadow-sm');
                };

                tombolFilter.forEach((tombol) => {
                    tombol.addEventListener('click', () => {
                        const filter = tombol.dataset.filter;
                        let tampil = 0;

                        baris.forEach((row) => {
                            const cocok = filter === 'semua' || row.dataset.status === filter;
                            row.classList.toggle('hidden', !cocok);
                            if (cocok) tampil++;
                        });

                        if (pesanKosong) {
                            pesanKosong.classList.toggle('hidden', tampil > 0);
                        }

                        aktifkanTombol(tombol);
                    });
                });
            });
        </script>
    @endpush
</x-admin-layout>

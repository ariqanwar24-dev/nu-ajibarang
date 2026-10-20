<x-admin-layout title="Kelola Berita">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-brand">Pusat Publikasi</p>
                <h1 class="mt-1 font-serif text-2xl font-semibold tracking-tight text-brand-deeper sm:text-3xl">Kelola Berita</h1>
                <p class="mt-1.5 text-sm text-brand-muted">Kelola, tinjau, dan terbitkan informasi organisasi dari satu tempat.</p>
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
        <section class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-brand-border bg-white p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">Total Berita</p>
                <p class="mt-1 text-3xl font-black text-slate-900">{{ $ringkasanStatus['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-brand-border bg-white p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">Sudah Terbit</p>
                <p class="mt-1 text-3xl font-black text-emerald-700">{{ $ringkasanStatus['terbit'] }}</p>
            </div>
            <div class="rounded-2xl border border-brand-border bg-white p-4 shadow-sm">
                <p class="text-[10.5px] font-extrabold uppercase tracking-wider text-slate-500">Masih Draft</p>
                <p class="mt-1 text-3xl font-black text-amber-700">{{ $ringkasanStatus['draft'] }}</p>
            </div>
        </section>

        {{-- Filter status --}}
        <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filter status berita">
            <button type="button" data-filter="semua"
                    class="filter-berita rounded-full bg-brand px-4 py-2 text-xs font-bold text-white shadow-sm">
                Semua ({{ $ringkasanStatus['total'] }})
            </button>
            <button type="button" data-filter="terbit"
                    class="filter-berita rounded-full border border-brand-border bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:border-brand hover:text-brand">
                Terbit ({{ $ringkasanStatus['terbit'] }})
            </button>
            <button type="button" data-filter="draft"
                    class="filter-berita rounded-full border border-brand-border bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:border-brand hover:text-brand">
                Draft ({{ $ringkasanStatus['draft'] }})
            </button>
        </div>

        <section class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm">
            @if ($berita->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left text-sm">
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
                                            'bg-emerald-100 text-emerald-800' => $item->sudahTerbit(),
                                            'bg-slate-200 text-slate-700' => ! $item->sudahTerbit(),
                                        ])>
                                            {{ $item->sudahTerbit() ? 'Terbit' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-xs text-slate-500">
                                        {{ $item->created_at?->format('d M Y') ?? '—' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap justify-end gap-1.5">
                                            <a href="{{ route('berita.show', $item) }}"
                                               class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-bold text-slate-600 hover:border-brand hover:text-brand">
                                                Lihat
                                            </a>
                                            <a href="{{ route('admin.berita.edit', $item) }}"
                                               class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-[11px] font-bold text-amber-900 hover:bg-amber-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.berita.destroy', $item) }}" method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-lg bg-red-100 px-2.5 py-1.5 text-[11px] font-bold text-red-700 hover:bg-red-200">
                                                    Hapus
                                                </button>
                                            </form>
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

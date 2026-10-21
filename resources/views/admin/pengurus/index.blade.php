<x-admin-layout title="Kelola Pengurus">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-brand">Struktur Organisasi</p>
                <h1 class="mt-1 font-serif text-2xl font-semibold tracking-tight text-brand-deeper sm:text-3xl">Kelola Pengurus</h1>
                <p class="mt-1.5 text-sm text-brand-muted">Data pengurus yang disimpan di sini tampil pada halaman struktur organisasi publik.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('pengurus.index') }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-xl border border-brand-border bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition hover:border-brand hover:text-brand">
                    Lihat Publik ↗
                </a>
                <a href="{{ route('admin.pengurus.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-brand px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-brand-dark">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>
                    </svg>
                    Tambah Pengurus
                </a>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-[1500px] px-4 py-6 sm:px-6 lg:px-8">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs font-semibold text-slate-500">
                @if ($pengurus->total() > 0)
                    Menampilkan {{ $pengurus->firstItem() }}–{{ $pengurus->lastItem() }} dari {{ $pengurus->total() }} data pengurus.
                @else
                    Belum ada data pengurus tersimpan.
                @endif
            </p>
            @if ($jumlahDemo > 0)
                <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wide text-amber-800">
                    {{ $jumlahDemo }} data demonstrasi
                </span>
            @endif
        </div>

        @if ($pengurus->isNotEmpty())
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($pengurus as $item)
                    <article class="overflow-hidden rounded-3xl border border-brand-border bg-white shadow-sm transition hover:border-brand/30 hover:shadow-md">
                        <div class="flex items-start gap-4 p-5">
                            @if ($item->foto_url)
                                <img src="{{ $item->foto_url }}" alt="Foto {{ $item->nama }}"
                                     class="h-28 w-24 shrink-0 rounded-2xl object-cover object-top" loading="lazy">
                            @else
                                <div class="flex h-28 w-24 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand to-brand-dark text-3xl font-black text-white/90">
                                    {{ mb_strtoupper(mb_substr($item->nama, 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="rounded-full bg-brand/10 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-brand">
                                        {{ $item->kategoriLabel() }}
                                    </span>
                                    @if ($item->is_demo)
                                        <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wide text-amber-800">
                                            Demo
                                        </span>
                                    @endif
                                </div>

                                <h2 class="mt-2 line-clamp-2 text-base font-black leading-snug text-slate-900">{{ $item->nama }}</h2>
                                <p class="mt-1 truncate text-sm font-bold text-brand">{{ $item->jabatan }}</p>
                                <p class="mt-1 truncate text-[10.5px] font-semibold uppercase tracking-wide text-slate-400">
                                    {{ $item->banomName() ?: $item->kategoriLabel() }} · Urutan {{ $item->urutan }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 border-t border-slate-100 bg-slate-50/70 p-4">
                            <a href="{{ route('admin.pengurus.edit', $item) }}"
                               class="rounded-lg bg-amber-100 px-3 py-2 text-xs font-bold text-amber-900 transition hover:bg-amber-200">
                                Edit
                            </a>
                            <form action="{{ route('admin.pengurus.destroy', $item) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data pengurus ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="rounded-lg bg-red-100 px-3 py-2 text-xs font-bold text-red-700 transition hover:bg-red-200">
                                    Hapus
                                </button>
                            </form>
                            <a href="{{ route('pengurus.show', $item) }}" target="_blank" rel="noopener"
                               class="ml-auto rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 transition hover:border-brand hover:text-brand">
                                Lihat ↗
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($pengurus->hasPages())
                <div class="mt-7 rounded-2xl border border-brand-border bg-white p-4">
                    {{ $pengurus->links() }}
                </div>
            @endif
        @else
            <section class="rounded-3xl border border-brand-border bg-white px-6 py-16 text-center shadow-sm">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-brand">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857a5.002 5.002 0 0 0-9.288 0M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </div>
                <h2 class="mt-4 text-lg font-black text-slate-800">Belum ada data pengurus</h2>
                <p class="mt-1 text-sm text-slate-500">Tambahkan susunan pengurus agar tampil pada halaman struktur organisasi.</p>
                <a href="{{ route('admin.pengurus.create') }}"
                   class="mt-5 inline-flex items-center gap-2 rounded-xl bg-brand px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-dark">
                    + Tambah Pengurus
                </a>
            </section>
        @endif
    </div>
</x-admin-layout>

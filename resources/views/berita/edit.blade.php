<x-admin-layout title="Edit Berita">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <nav class="flex flex-wrap items-center gap-1.5 text-[11px] font-semibold text-slate-400" aria-label="Breadcrumb">
                    <a href="{{ route('admin.index') }}" class="hover:text-brand">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.berita.index') }}" class="hover:text-brand">Kelola Berita</a>
                    <span>/</span>
                    <span class="text-brand">Edit Berita</span>
                </nav>
                <h1 class="mt-1.5 font-serif text-2xl font-semibold tracking-tight text-brand-deeper sm:text-3xl">Edit Berita</h1>
                <p class="mt-1.5 max-w-2xl text-sm text-brand-muted">
                    Perbarui konten lalu ajukan review. Perubahan pada berita terbit tidak langsung tampil sebelum disetujui super admin.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('berita.show', $berita) }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-xl border border-brand-border bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition hover:border-brand hover:text-brand">
                    Lihat Berita ↗
                </a>
                <a href="{{ route('admin.berita.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-brand-border bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition hover:border-brand hover:text-brand">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    @include('admin.berita._form', ['berita' => $berita])
</x-admin-layout>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Struktur Pengurus - NU Ajibarang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
<main class="mx-auto max-w-6xl p-5 md:p-8">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div><p class="text-sm font-bold text-emerald-700">MWC NU AJIBARANG</p><h1 class="mt-1 text-3xl font-black">Kelola Struktur Pengurus</h1></div>
        <div class="flex gap-2"><a href="{{ route('admin.index') }}" class="rounded-lg border bg-white px-4 py-2 font-semibold">Dashboard</a><a href="{{ route('admin.pengurus.create') }}" class="rounded-lg bg-emerald-800 px-4 py-2 font-bold text-white">+ Tambah Pengurus</a></div>
    </div>
    @if(session('success'))<div class="mb-4 rounded-lg bg-emerald-100 p-3 text-emerald-900">{{ session('success') }}</div>@endif
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($pengurus as $item)
        <article class="overflow-hidden rounded-2xl border bg-white shadow-sm">
            <div class="flex items-center gap-4 p-5">
                @if($item->foto)<img src="{{ asset('storage/'.$item->foto) }}" alt="Foto {{ $item->nama }}" class="h-20 w-20 rounded-xl object-cover">@else<div class="flex h-20 w-20 items-center justify-center rounded-xl bg-emerald-50 text-2xl font-black text-emerald-800">NU</div>@endif
                <div class="min-w-0"><h2 class="font-extrabold">{{ $item->nama }}</h2><p class="mt-1 text-sm text-emerald-800">{{ $item->jabatan }}</p><p class="mt-1 text-xs text-slate-500">{{ $item->tingkatan ?: 'Tingkatan belum diisi' }}</p></div>
            </div>
            <div class="flex gap-2 border-t bg-slate-50 p-4">
                <a href="{{ route('admin.pengurus.edit', $item) }}" class="rounded-lg bg-amber-100 px-3 py-2 text-sm font-bold text-amber-900">Edit</a>
                <form action="{{ route('admin.pengurus.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data pengurus ini?')">@csrf @method('DELETE')<button class="rounded-lg bg-red-100 px-3 py-2 text-sm font-bold text-red-800">Hapus</button></form>
            </div>
        </article>
        @empty
        <p class="rounded-xl border bg-white p-8 text-slate-500 sm:col-span-2 lg:col-span-3">Belum ada data pengurus.</p>
        @endforelse
    </div>
</main>
</body>
</html>

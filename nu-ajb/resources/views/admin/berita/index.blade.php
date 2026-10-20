<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Berita - NU Ajibarang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
<main class="mx-auto max-w-6xl p-5 md:p-8">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div><p class="text-sm font-bold text-emerald-700">MWC NU AJIBARANG</p><h1 class="mt-1 text-3xl font-black">Kelola Berita</h1></div>
        <div class="flex gap-2">
            <a href="{{ route('admin.index') }}" class="rounded-lg border bg-white px-4 py-2 font-semibold">Dashboard</a>
            <a href="{{ route('admin.berita.create') }}" class="rounded-lg bg-emerald-800 px-4 py-2 font-bold text-white">+ Tulis Berita</a>
        </div>
    </div>
    @if(session('success'))<div class="mb-4 rounded-lg bg-emerald-100 p-3 text-emerald-900">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800">{{ $errors->first() }}</div>@endif
    <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600"><tr><th class="p-4">Berita</th><th class="p-4">Tanggal</th><th class="p-4">Aksi</th></tr></thead>
                <tbody class="divide-y">
                @forelse($berita as $item)
                    <tr>
                        <td class="p-4"><div class="flex items-center gap-3">
                            @if($item->gambar)<img class="h-16 w-20 rounded-lg object-cover" src="{{ asset('storage/'.$item->gambar) }}" alt="">@endif
                            <div><p class="font-bold">{{ $item->judul }}</p><p class="mt-1 max-w-xl text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 100) }}</p></div>
                        </div></td>
                        <td class="whitespace-nowrap p-4 text-slate-500">{{ $item->created_at?->format('d M Y') ?? '—' }}</td>
                        <td class="p-4"><div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.berita.edit', $item) }}" class="rounded-lg bg-amber-100 px-3 py-2 font-bold text-amber-900">Edit</a>
                            <form action="{{ route('admin.berita.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">@csrf @method('DELETE')<button class="rounded-lg bg-red-100 px-3 py-2 font-bold text-red-800">Hapus</button></form>
                            <a href="{{ route('berita.show', $item) }}" class="rounded-lg bg-slate-100 px-3 py-2 font-bold">Lihat</a>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="p-10 text-center text-slate-500">Belum ada berita.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>

<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Berita - NU Ajibarang</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-slate-50 p-5 text-slate-800"><main class="mx-auto max-w-3xl rounded-2xl border bg-white p-6 shadow-sm md:p-8">
<div class="mb-6 flex items-center justify-between gap-3"><h1 class="text-2xl font-black">Edit Berita</h1><a class="font-semibold text-emerald-800" href="{{ route('admin.berita.index') }}">← Kembali</a></div>
@if($errors->any())<div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<form action="{{ route('admin.berita.update', $berita) }}" method="POST" enctype="multipart/form-data" class="space-y-5">@csrf @method('PUT')
<div><label for="judul" class="mb-1 block font-bold">Judul Berita</label><input class="w-full rounded-lg border-slate-300" id="judul" name="judul" value="{{ old('judul', $berita->judul) }}" required></div>
<div><label for="isi" class="mb-1 block font-bold">Isi Berita</label><textarea class="w-full rounded-lg border-slate-300" id="isi" name="isi" rows="10" required>{{ old('isi', $berita->isi) }}</textarea></div>
@if($berita->gambar)<div><p class="mb-2 font-bold">Gambar saat ini</p><img src="{{ asset('storage/'.$berita->gambar) }}" alt="{{ $berita->judul }}" class="max-h-64 rounded-xl object-contain"></div>@endif
<div><label for="gambar" class="mb-1 block font-bold">Ganti Gambar (opsional)</label><input class="w-full rounded-lg border p-2" type="file" id="gambar" name="gambar" accept="image/*"><p class="mt-1 text-xs text-slate-500">Maksimal 2 MB. Kosongkan jika tidak ingin mengganti gambar.</p></div>
<div class="flex gap-3"><button class="rounded-lg bg-emerald-800 px-5 py-3 font-bold text-white">Simpan Perubahan</button><a class="rounded-lg border px-5 py-3 font-semibold" href="{{ route('admin.berita.index') }}">Batal</a></div>
</form></main></body></html>

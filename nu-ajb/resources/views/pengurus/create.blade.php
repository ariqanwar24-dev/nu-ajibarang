<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Tambah Pengurus - NU Ajibarang</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-slate-50 p-5 text-slate-800"><main class="mx-auto max-w-3xl rounded-2xl border bg-white p-6 shadow-sm md:p-8">
<div class="mb-6 flex items-center justify-between gap-3"><h1 class="text-2xl font-black">Tambah Pengurus</h1><a class="font-semibold text-emerald-800" href="{{ route('admin.pengurus.index') }}">← Kembali</a></div>
@if($errors->any())<div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<form action="{{ route('admin.pengurus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">@csrf
<div><label class="mb-1 block font-bold" for="nama">Nama Pengurus</label><input class="w-full rounded-lg border-slate-300" id="nama" name="nama" value="{{ old('nama') }}" required></div>
<div><label class="mb-1 block font-bold" for="jabatan">Jabatan</label><input class="w-full rounded-lg border-slate-300" id="jabatan" name="jabatan" value="{{ old('jabatan') }}" required></div>
<div><label class="mb-1 block font-bold" for="tingkatan">Tingkatan</label><select class="w-full rounded-lg border-slate-300" id="tingkatan" name="tingkatan"><option value="">Pilih tingkatan (opsional)</option>@foreach(['mustasyar'=>'Dewan Mustasyar','syuriyah'=>'Syuriyah','tanfidziyah'=>'Tanfidziyah','banom'=>'Banom'] as $key=>$label)<option value="{{ $key }}" @selected(old('tingkatan')===$key)>{{ $label }}</option>@endforeach</select></div>
<div><label class="mb-1 block font-bold" for="foto">Foto Pengurus (opsional)</label><input class="w-full rounded-lg border p-2" type="file" id="foto" name="foto" accept="image/*"><p class="mt-1 text-xs text-slate-500">Format gambar, maksimal 2 MB.</p></div>
<div class="flex gap-3"><button class="rounded-lg bg-emerald-800 px-5 py-3 font-bold text-white">Simpan Pengurus</button><a class="rounded-lg border px-5 py-3 font-semibold" href="{{ route('admin.pengurus.index') }}">Batal</a></div>
</form></main></body></html>

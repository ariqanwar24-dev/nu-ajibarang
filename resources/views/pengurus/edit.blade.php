<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Pengurus - NU Ajibarang</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-slate-50 p-5 text-slate-800"><main class="mx-auto max-w-3xl rounded-2xl border bg-white p-6 shadow-sm md:p-8">
<div class="mb-6 flex items-center justify-between gap-3"><h1 class="text-2xl font-black">Edit Pengurus</h1><a class="font-semibold text-emerald-800" href="{{ route('admin.pengurus.index') }}">← Kembali</a></div>
@if($errors->any())<div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<form action="{{ route('admin.pengurus.update', $pengurus) }}" method="POST" enctype="multipart/form-data" class="space-y-5">@csrf @method('PUT')
<div><label class="mb-1 block font-bold" for="nama">Nama Pengurus</label><input class="w-full rounded-lg border-slate-300" id="nama" name="nama" value="{{ old('nama', $pengurus->nama) }}" required></div>
<div><label class="mb-1 block font-bold" for="jabatan">Jabatan</label><input class="w-full rounded-lg border-slate-300" id="jabatan" name="jabatan" value="{{ old('jabatan', $pengurus->jabatan) }}" required></div>
<div><label class="mb-1 block font-bold" for="tingkatan">Tingkatan</label><select class="w-full rounded-lg border-slate-300" id="tingkatan" name="tingkatan"><option value="">Pilih tingkatan (opsional)</option>@foreach(['mustasyar'=>'Dewan Mustasyar','syuriyah'=>'Syuriyah','tanfidziyah'=>'Tanfidziyah','banom'=>'Banom'] as $key=>$label)<option value="{{ $key }}" @selected(old('tingkatan', $pengurus->tingkatan)===$key)>{{ $label }}</option>@endforeach</select></div>
@if($pengurus->foto)<div><p class="mb-2 font-bold">Foto saat ini</p><img src="{{ asset('storage/'.$pengurus->foto) }}" alt="{{ $pengurus->nama }}" class="h-36 w-36 rounded-xl object-cover"></div>@endif
<div><label class="mb-1 block font-bold" for="foto">Ganti Foto (opsional)</label><input class="w-full rounded-lg border p-2" type="file" id="foto" name="foto" accept="image/*"><p class="mt-1 text-xs text-slate-500">Maksimal 2 MB. Kosongkan jika tidak ingin mengganti foto.</p></div>
<div class="flex gap-3"><button class="rounded-lg bg-emerald-800 px-5 py-3 font-bold text-white">Simpan Perubahan</button><a class="rounded-lg border px-5 py-3 font-semibold" href="{{ route('admin.pengurus.index') }}">Batal</a></div>
</form></main></body></html>

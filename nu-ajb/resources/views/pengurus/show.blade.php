<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $pengurus->nama }} - NU Ajibarang</title>
</head>
<body>

    <a href="{{ route('pengurus.index') }}">
        ← Kembali ke daftar pengurus
    </a>

    <hr>

    <h1>{{ $pengurus->nama }}</h1>

    <p>
        <strong>Jabatan:</strong>
        {{ $pengurus->jabatan }}
    </p>

    <hr>

    {{-- Tombol khusus admin --}}
    @auth
        @if(auth()->user()->is_admin)

            <a href="{{ route('admin.pengurus.edit', $pengurus) }}">
                Edit Pengurus
            </a>

            <form
                action="{{ route('admin.pengurus.destroy', $pengurus) }}"
                method="POST"
                style="display: inline;"
            >
                @csrf
                @method('DELETE')

                <button type="submit">
                    Hapus Pengurus
                </button>
            </form>

        @endif
    @endauth

</body>
</html>
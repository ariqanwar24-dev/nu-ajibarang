
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anggota NU Ajibarang</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 16px;
            background: #f1f5f1;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 650px;
            margin: auto;
        }

        .header {
            padding: 24px;
            border-radius: 14px 14px 0 0;
            background: #176b3a;
            color: white;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 25px;
        }

        .header p {
            margin: 0;
            color: #d9f5e2;
            font-size: 14px;
        }

        .form-card {
            padding: 26px;
            background: white;
            border-radius: 0 0 14px 14px;
            box-shadow: 0 8px 25px #0000000d;
        }

        .pesan {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
        }

        .error {
            padding: 12px 12px 12px 30px;
            margin-bottom: 18px;
            border-radius: 8px;
            background: #fee2e2;
            color: #991b1b;
        }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            font: inherit;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: 2px solid #86c99f;
            border-color: #176b3a;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .foto-lama {
            margin-bottom: 12px;
        }

        .foto-lama img {
            display: block;
            width: 100px;
            height: 125px;
            margin-top: 8px;
            border-radius: 8px;
            object-fit: cover;
        }

        .bantuan {
            display: block;
            margin-top: 6px;
            color: #6b7280;
            font-size: 12px;
        }

        .tombol {
            display: inline-block;
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #176b3a;
            color: white;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .tombol:hover {
            background: #12552e;
        }

        .kembali {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #176b3a;
            text-decoration: none;
            font-size: 14px;
        }

        .kembali:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            body {
                padding: 16px 10px;
            }

            .header,
            .form-card {
                padding: 20px;
            }

            .header h1 {
                font-size: 21px;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header">
            <h1>{{ $anggota ? 'Perbarui Data Anggota' : 'Pendaftaran Anggota' }}</h1>
            <p>NU Kecamatan Ajibarang</p>
        </header>

        <section class="form-card">
            @if (session('success'))
                <div class="pesan">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <ul class="error">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('anggota.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label for="nama_anggota">Nama lengkap</label>
                    <input
                        id="nama_anggota"
                        type="text"
                        name="nama_anggota"
                        value="{{ old('nama_anggota', $anggota?->nama_anggota) }}"
                        required
                    >
                </div>

                <div class="field">
                    <label for="alamat">Alamat lengkap</label>
                    <textarea id="alamat" name="alamat" required>{{ old('alamat', $anggota?->alamat) }}</textarea>
                </div>

                <div class="field">
                    <label for="tanggal_lahir">Tanggal lahir</label>
                    <input
                        id="tanggal_lahir"
                        type="date"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $anggota?->tanggal_lahir?->format('Y-m-d')) }}"
                    >
                </div>

                <div class="field">
                    <label for="jenis_kelamin">Jenis kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="L" @selected(old('jenis_kelamin', $anggota?->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $anggota?->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                </div>

                <div class="field">
                    <label for="no_hp">Nomor HP</label>
                    <input
                        id="no_hp"
                        type="text"
                        name="no_hp"
                        value="{{ old('no_hp', $anggota?->no_hp) }}"
                        required
                    >
                </div>

                <div class="field">
                    <label for="foto">Foto anggota</label>

                    @if ($anggota?->foto)
                        <div class="foto-lama">
                            <p>Foto yang tersimpan saat ini:</p>
                            <img src="{{ asset('storage/' . $anggota->foto) }}" alt="Foto anggota saat ini">
                        </div>
                    @endif

                    <input id="foto" type="file" name="foto" accept="image/*">
                    <span class="bantuan">Opsional, maksimal 2 MB. Kosongkan jika tidak ingin mengganti foto.</span>
                </div>

                <button class="tombol" type="submit">
                    {{ $anggota ? 'Perbarui Data' : 'Kirim Pendaftaran' }}
                </button>
            </form>

            <a class="kembali" href="{{ route('home') }}">← Kembali ke Beranda</a>
        </section>
    </main>
</body>
</html>

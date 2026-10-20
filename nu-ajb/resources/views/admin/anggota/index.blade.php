<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Anggota - Admin NU Ajibarang</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #f4f7f5;
            color: #24332b;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0 0 8px;
        }

        .back {
            color: #087443;
            text-decoration: none;
        }

        .card {
            background: white;
            padding: 22px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #0000000a;
        }

        .data {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .data p {
            margin: 0;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            background: #eee;
            font-size: 13px;
        }

        .pending {
            background: #fff0c2;
            color: #795600;
        }

        .approved {
            background: #d9f8e5;
            color: #126638;
        }

        .rejected {
            background: #ffe0e0;
            color: #a12020;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .actions form {
            flex: 1;
            min-width: 230px;
        }

        textarea {
            width: 100%;
            min-height: 80px;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font: inherit;
        }

        button {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        .approve {
            background: #087443;
        }

        .reject {
            background: #b42318;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .success {
            background: #d9f8e5;
            color: #126638;
        }

        .error {
            background: #ffe0e0;
            color: #a12020;
        }

        .photo {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        @media (max-width: 600px) {
            body {
                padding: 14px;
            }

            .card {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header">
            <div>
                <h1>Verifikasi Anggota</h1>
                <p>Periksa data anggota sebelum menyetujui kartu anggota.</p>
            </div>

            <a class="back" href="{{ route('admin.index') }}">
                ← Kembali ke Dashboard
            </a>
        </header>

        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="message error">
                <strong>Ada kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @forelse ($anggotas as $anggota)
            <section class="card">
                <h2>{{ $anggota->nama_anggota }}</h2>

                <p>
                    Status:
                    <span class="status {{ $anggota->status_verifikasi }}">
                        {{ ucfirst($anggota->status_verifikasi) }}
                    </span>
                </p>

                @if ($anggota->foto)
                    <p>
                        <img
                            class="photo"
                            src="{{ asset('storage/' . $anggota->foto) }}"
                            alt="Foto {{ $anggota->nama_anggota }}"
                        >
                    </p>
                @endif

                <div class="data">
                    <p>
                        <strong>Email akun:</strong><br>
                        {{ $anggota->user?->email ?? 'Tidak tersedia' }}
                    </p>

                    <p>
                        <strong>Alamat:</strong><br>
                        {{ $anggota->alamat }}
                    </p>

                    <p>
                        <strong>Tanggal lahir:</strong><br>
                        {{ $anggota->tanggal_lahir?->format('d-m-Y') ?? 'Belum diisi' }}
                    </p>

                    <p>
                        <strong>Jenis kelamin:</strong><br>
                        {{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </p>

                    <p>
                        <strong>No. HP:</strong><br>
                        {{ $anggota->no_hp }}
                    </p>
                </div>

                @if ($anggota->status_verifikasi === 'rejected')
                    <p>
                        <strong>Catatan penolakan:</strong><br>
                        {{ $anggota->catatan_verifikasi ?? 'Tidak ada catatan.' }}
                    </p>
                @endif

                <div class="actions">
                    <form
                        action="{{ route('admin.anggota.verifikasi', $anggota) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PATCH')

                        <input
                            type="hidden"
                            name="status_verifikasi"
                            value="approved"
                        >

                        <button class="approve" type="submit">
                            Setujui Anggota
                        </button>
                    </form>

                    <form
                        action="{{ route('admin.anggota.verifikasi', $anggota) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PATCH')

                        <input
                            type="hidden"
                            name="status_verifikasi"
                            value="rejected"
                        >

                        <label for="catatan-{{ $anggota->id }}">
                            Alasan penolakan
                        </label>

                        <textarea
                            id="catatan-{{ $anggota->id }}"
                            name="catatan_verifikasi"
                            placeholder="Jelaskan data yang perlu diperbaiki..."
                            required
                        >{{ old('catatan_verifikasi') }}</textarea>

                        <button class="reject" type="submit">
                            Tolak Anggota
                        </button>
                    </form>
                </div>

                <form action="{{ route('admin.anggota.destroy', $anggota) }}" method="POST"
                      onsubmit="return confirm('Hapus permintaan anggota ini? Data permintaan tidak dapat dipulihkan.')"
                      style="margin-top:16px">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#475569">Hapus Permintaan</button>
                </form>
            </section>
        @empty
            <section class="card">
                <p>Belum ada data anggota yang masuk.</p>
            </section>
        @endforelse
    </main>
</body>
</html>
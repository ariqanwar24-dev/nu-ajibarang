<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota NU Ajibarang</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f1;
            margin: 0;
            padding: 30px;
            color: #222;
        }

        .container {
            max-width: 700px;
            margin: auto;
        }

        .kartu {
            background: white;
            border: 2px solid #176b3a;
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background: #176b3a;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .header h2 {
            margin: 0 0 8px;
        }

        .header p {
            margin: 0;
        }

        .isi {
            display: flex;
            gap: 20px;
            padding: 24px;
            align-items: flex-start;
        }

        .foto {
            width: 120px;
            height: 150px;
            object-fit: cover;
            border: 1px solid #bbb;
        }

        .tanpa-foto {
            width: 120px;
            height: 150px;
            border: 1px dashed #999;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 12px;
        }

        .data {
            flex: 1;
            overflow-wrap: anywhere;
        }

        .baris {
            margin-bottom: 12px;
        }

        .label {
            display: inline-block;
            width: 125px;
            font-weight: bold;
        }

        .aksi {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .tombol {
            display: inline-block;
            padding: 10px 16px;
            background: #176b3a;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .sekunder {
            background: #555;
        }

@media print {
    @page {
        size: A4;
        margin: 15mm;
    }

    body {
        background: white;
        padding: 0;
        margin: 0;
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }

    .container {
        max-width: none;
        margin: 0;
    }

    .kartu {
        width: 85.6mm;
        min-height: 54mm;
        box-sizing: border-box;
        border: 1px solid #176b3a;
        border-radius: 3mm;
        overflow: hidden;
        break-inside: avoid;
    }

    .header {
        padding: 8px;
    }

    .header h2 {
        font-size: 14px;
        margin-bottom: 4px;
    }

    .header p {
        font-size: 10px;
    }

    .isi {
        gap: 10px;
        padding: 10px;
        align-items: flex-start;
    }

    .foto,
    .tanpa-foto {
        width: 22mm;
        height: 30mm;
        box-sizing: border-box;
    }

    .data {
        font-size: 9px;
    }

    .baris {
        margin-bottom: 6px;
    }

    .label {
        width: 75px;
    }

    .aksi {
        display: none !important;
    }
}

    </style>
</head>
<body>
    <main class="container">
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <section class="kartu">
            <header class="header">
                <h2>KARTU DATA ANGGOTA</h2>
                <p>NU Kecamatan Ajibarang</p>
            </header>

            <div class="isi">
                <div>
                    @if ($anggota->foto)
                        <img
                            class="foto"
                            src="{{ asset('storage/' . $anggota->foto) }}"
                            alt="Foto {{ $anggota->nama_anggota }}"
                        >
                    @else
                        <div class="tanpa-foto">Foto belum tersedia</div>
                    @endif
                </div>

                <div class="data">
                    <div class="baris">
                        <span class="label">Nama</span>
                        : {{ $anggota->nama_anggota }}
                    </div>

                    <div class="baris">
                        <span class="label">Alamat</span>
                        : {{ $anggota->alamat }}
                    </div>

                    <div class="baris">
                        <span class="label">Tanggal Lahir</span>
                        : {{ $anggota->tanggal_lahir?->format('d-m-Y') ?? '-' }}
                    </div>

                    <div class="baris">
                        <span class="label">Jenis Kelamin</span>
                        : {{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </div>

                    <div class="baris">
                        <span class="label">Nomor HP</span>
                        : {{ $anggota->no_hp }}
                    </div>
                </div>
            </div>
        </section>

        <div class="aksi">
            <a class="tombol sekunder" href="{{ route('anggota.create') }}">
                Edit Data
            </a>

            <button class="tombol" onclick="window.print()">
                Cetak Kartu
            </button>
        </div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Berita — NU Ajibarang</title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            font-family: Arial, Helvetica, sans-serif;
            color: #202820;
            background: #f5f6f2;
            font-synthesis: none;
        }

        body {
            margin: 0;
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button, input, textarea {
            font: inherit;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            display: flex;
            flex-direction: column;
            width: 245px;
            min-height: 100vh;
            background: #174b38;
            color: #fff;
            z-index: 5;
        }

        .brand {
            padding: 27px 24px;
            border-bottom: 1px solid #ffffff20;
        }

        .brand small {
            color: #b8d9c3;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .brand h2 {
            margin: 8px 0 5px;
            font-size: 23px;
            font-weight: 600;
        }

        .brand p {
            margin: 0;
            color: #c6d9cc;
            font-size: 12px;
        }

        .navigation {
            display: grid;
            gap: 6px;
            padding: 20px 14px;
        }

        .navigation a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 14px;
            border-radius: 7px;
            color: #d9e7dd;
            font-size: 14px;
            transition: background .15s;
        }

        .navigation a:hover,
        .navigation a.active {
            background: #ffffff18;
            color: white;
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 17px;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 22px;
            border-top: 1px solid #ffffff20;
            color: #c6d9cc;
            font-size: 13px;
        }

        .sidebar-bottom a:hover {
            color: white;
        }

        .main {
            min-height: 100vh;
            margin-left: 245px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            min-height: 76px;
            padding: 18px 36px;
            background: #fff;
            border-bottom: 1px solid #e4e7e1;
        }

        .breadcrumb {
            margin-bottom: 5px;
            color: #858b84;
            font-size: 12px;
        }

        .topbar-title {
            font-size: 14px;
            font-weight: 600;
        }

        .topbar-link {
            padding: 10px 14px;
            border: 1px solid #dfe3dc;
            border-radius: 7px;
            font-size: 13px;
            white-space: nowrap;
        }

        .topbar-link:hover {
            background: #f5f6f2;
        }

        .content {
            width: 100%;
            max-width: 1250px;
            margin: 0 auto;
            padding: 36px;
        }

        .page-heading {
            margin-bottom: 27px;
        }

        .eyebrow {
            margin: 0 0 10px;
            color: #39734f;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .page-heading h1 {
            margin: 0;
            font-size: 30px;
            font-weight: 650;
            letter-spacing: -.8px;
        }

        .page-heading p {
            max-width: 560px;
            margin: 10px 0 0;
            color: #747b73;
            font-size: 14px;
            line-height: 1.7;
        }

        .errors {
            margin-bottom: 22px;
            padding: 16px 18px;
            border: 1px solid #f0caca;
            border-radius: 9px;
            background: #fff4f4;
            color: #a72f2f;
            font-size: 13px;
        }

        .errors ul {
            margin-bottom: 0;
            padding-left: 20px;
            line-height: 1.8;
        }

        .form-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 290px;
            align-items: start;
            gap: 22px;
        }

        .panel {
            min-width: 0;
            overflow: hidden;
            border: 1px solid #e0e4dc;
            border-radius: 11px;
            background: #fff;
        }

        .panel-header {
            padding: 20px 23px;
            border-bottom: 1px solid #edf0ea;
        }

        .panel-header h2 {
            margin: 0;
            font-size: 15px;
            font-weight: 650;
        }

        .panel-header p {
            margin: 7px 0 0;
            color: #858b83;
            font-size: 12px;
            line-height: 1.6;
        }

        .panel-body {
            display: grid;
            gap: 24px;
            padding: 24px;
        }

        .field label,
        .field-label {
            display: block;
            margin-bottom: 9px;
            font-size: 13px;
            font-weight: 600;
        }

        .required {
            color: #b33a35;
        }

        .field input[type="text"],
        .field textarea {
            display: block;
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #d9ded6;
            border-radius: 7px;
            outline: none;
            background: white;
            color: #202820;
            font-size: 13px;
            transition: border-color .15s, box-shadow .15s;
        }

        .field input[type="text"] {
            min-height: 45px;
        }

        .field textarea {
            min-height: 320px;
            resize: vertical;
            line-height: 1.8;
        }

        .field input:focus,
        .field textarea:focus {
            border-color: #39734f;
            box-shadow: 0 0 0 3px #39734f15;
        }

        .field input::placeholder,
        .field textarea::placeholder {
            color: #a1a69f;
        }

        .field-help {
            margin: 8px 0 0;
            color: #8a9088;
            font-size: 11px;
            line-height: 1.6;
        }

        .side-panels {
            display: grid;
            gap: 18px;
        }

        .upload-area {
            display: flex;
            min-height: 175px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 14px;
            border: 1px dashed #cbd4c9;
            border-radius: 8px;
            background: #fafbf8;
            text-align: center;
            cursor: pointer;
            transition: background .15s, border-color .15s;
        }

        .upload-area:hover {
            border-color: #39734f;
            background: #f3f7f1;
        }

        .upload-icon {
            display: grid;
            width: 43px;
            height: 43px;
            margin-bottom: 13px;
            place-items: center;
            border-radius: 50%;
            background: #e6efe6;
            color: #245c3b;
            font-size: 21px;
        }

        .upload-title {
            font-size: 13px;
            font-weight: 600;
        }

        .upload-subtitle {
            margin-top: 7px;
            color: #8a9088;
            font-size: 11px;
            line-height: 1.6;
        }

        .upload-filename {
            margin-top: 10px;
            color: #28734f;
            font-size: 11px;
            overflow-wrap: anywhere;
        }

        .file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            clip-path: inset(50%);
        }

        .publish-body {
            padding: 20px;
        }

        .publish-body p {
            margin: 0;
            color: #777e75;
            font-size: 12px;
            line-height: 1.8;
        }

        .btn-primary {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 13px 16px;
            border: 0;
            border-radius: 7px;
            background: #174b38;
            color: white;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-primary:hover {
            background: #103b2b;
        }

        .btn-cancel {
            display: block;
            margin-top: 8px;
            padding: 12px;
            border-radius: 7px;
            color: #626a61;
            font-size: 13px;
            text-align: center;
        }

        .btn-cancel:hover {
            background: #f3f4f1;
        }

        .footer {
            margin-top: 34px;
            padding: 20px 0;
            border-top: 1px solid #e0e4dc;
            color: #92978f;
            font-size: 11px;
        }

        @media (max-width: 1050px) {
            .content {
                padding: 28px 22px;
            }

            .form-layout {
                grid-template-columns: minmax(0, 1fr) 250px;
                gap: 16px;
            }

            .topbar {
                padding: 18px 22px;
            }
        }

        @media (max-width: 800px) {
            .sidebar {
                position: static;
                width: 100%;
                min-height: auto;
            }

            .brand {
                padding: 18px 20px;
            }

            .brand h2 {
                font-size: 20px;
            }

            .navigation {
                display: flex;
                overflow-x: auto;
                padding: 10px 12px;
            }

            .navigation a {
                flex-shrink: 0;
                padding: 11px 13px;
            }

            .sidebar-bottom {
                display: none;
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                min-height: 65px;
                padding: 14px 18px;
            }

            .content {
                padding: 25px 18px;
            }

            .form-layout {
                grid-template-columns: 1fr;
            }

            .side-panels {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                align-items: start;
            }

            .page-heading h1 {
                font-size: 27px;
            }
        }

        @media (max-width: 520px) {
            .topbar-link {
                padding: 9px;
                font-size: 11px;
            }

            .side-panels {
                grid-template-columns: 1fr;
            }

            .panel-body {
                padding: 18px;
            }

            .panel-header {
                padding: 18px;
            }

            .field textarea {
                min-height: 250px;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <small>MWC NU</small>
            <h2>Ajibarang</h2>
            <p>Panel administrasi</p>
        </div>

        <nav class="navigation">
            <a href="{{ route('admin.index') }}">
                <span class="nav-icon">⌂</span>
                Dashboard
            </a>

            <a href="{{ route('admin.berita.create') }}" class="active">
                <span class="nav-icon">▤</span>
                Berita
            </a>

            <a href="{{ route('admin.pengurus.create') }}">
                <span class="nav-icon">♧</span>
                Pengurus
            </a>

            <a href="{{ route('admin.anggota.index') }}">
                <span class="nav-icon">♙</span>
                Anggota
            </a>
        </nav>

        <div class="sidebar-bottom">
            <a href="{{ route('home') }}">↗ &nbsp; Lihat website publik</a>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div>
                <div class="breadcrumb">Administrator / Berita</div>
                <div class="topbar-title">Buat publikasi baru</div>
            </div>

            <a href="{{ route('berita.index') }}" class="topbar-link">
                Lihat berita ↗
            </a>
        </header>

        <div class="content">
            <div class="page-heading">
                <div class="eyebrow">Pusat publikasi</div>
                <h1>Tulis berita</h1>
                <p>
                    Bagikan kegiatan, informasi, dan kabar terbaru dari
                    NU Kecamatan Ajibarang melalui publikasi berita.
                </p>
            </div>

            @if ($errors->any())
                <div class="errors">
                    <strong>Ada data yang perlu diperbaiki.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('admin.berita.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="form-layout">
                    <section class="panel">
                        <div class="panel-header">
                            <h2>Konten berita</h2>
                            <p>Isi informasi utama yang akan dipublikasikan.</p>
                        </div>

                        <div class="panel-body">
                            <div class="field">
                                <label for="judul">
                                    Judul berita <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="judul"
                                    name="judul"
                                    value="{{ old('judul') }}"
                                    placeholder="Masukkan judul berita"
                                    required
                                >
                            </div>

                            <div class="field">
                                <label for="isi">
                                    Isi berita <span class="required">*</span>
                                </label>

                                <textarea
                                    id="isi"
                                    name="isi"
                                    rows="14"
                                    placeholder="Mulai tulis isi berita di sini..."
                                    required
                                >{{ old('isi') }}</textarea>

                                <p class="field-help">
                                    Gunakan paragraf yang jelas agar berita mudah dibaca.
                                </p>
                            </div>
                        </div>
                    </section>

                    <div class="side-panels">
                        <section class="panel">
                            <div class="panel-header">
                                <h2>Gambar utama</h2>
                                <p>Tambahkan gambar pendukung berita.</p>
                            </div>

                            <div class="panel-body">
                                <label for="gambar" class="upload-area">
                                    <span class="upload-icon">↑</span>
                                    <span class="upload-title">Pilih gambar</span>
                                    <span class="upload-subtitle">
                                        Klik untuk memilih file gambar dari perangkat.
                                    </span>
                                    <span id="nama-file" class="upload-filename"></span>
                                </label>

                                <input
                                    type="file"
                                    id="gambar"
                                    name="gambar"
                                    accept="image/*"
                                    class="file-input"
                                >

                                <p class="field-help">
                                    Gambar bersifat opsional. Ikuti batas ukuran
                                    yang ditetapkan oleh validasi aplikasi.
                                </p>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="panel-header">
                                <h2>Publikasi</h2>
                                <p>Pastikan informasi sudah benar sebelum disimpan.</p>
                            </div>

                            <div class="publish-body">
                                <p>
                                    Setelah disimpan, data akan diproses oleh
                                    sistem sesuai aturan publikasi yang berlaku.
                                </p>

                                <button type="submit" class="btn-primary">
                                    Simpan berita
                                </button>

                                <a href="{{ route('admin.index') }}" class="btn-cancel">
                                    Batal
                                </a>
                            </div>
                        </section>
                    </div>
                </div>
            </form>

            <footer class="footer">
                © {{ date('Y') }} MWC NU Kecamatan Ajibarang
            </footer>
        </div>
    </main>

    <script>
        const inputGambar = document.getElementById('gambar');
        const namaFile = document.getElementById('nama-file');

        inputGambar.addEventListener('change', function () {
            namaFile.textContent = this.files.length
                ? this.files[0].name
                : '';
        });
    </script>
</body>
</html>

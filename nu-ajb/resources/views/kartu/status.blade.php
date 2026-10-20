
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Verifikasi Anggota - NU Ajibarang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 px-4 py-12 text-gray-800">

    <main class="mx-auto max-w-xl rounded-2xl bg-white p-6 shadow-lg sm:p-8">
        <div class="mb-6 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-3xl">
                @if ($anggota->status_verifikasi === 'approved')
                    ✓
                @elseif ($anggota->status_verifikasi === 'rejected')
                    !
                @else
                    …
                @endif
            </div>

            <h1 class="text-2xl font-bold">Status Verifikasi Anggota</h1>
            <p class="mt-2 text-gray-500">
                Halo, {{ $anggota->nama_anggota }}.
            </p>
        </div>

        @if ($anggota->status_verifikasi === 'pending')
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                <h2 class="text-lg font-bold text-amber-800">
                    Menunggu Verifikasi
                </h2>
                <p class="mt-2 text-sm leading-6 text-amber-900">
                    Data kamu sedang diperiksa oleh admin.
                    Kartu anggota belum bisa dicetak sampai pendaftaran disetujui.
                </p>
            </div>

        @elseif ($anggota->status_verifikasi === 'rejected')
            <div class="rounded-xl border border-red-200 bg-red-50 p-5">
                <h2 class="text-lg font-bold text-red-800">
                    Data Belum Disetujui
                </h2>
                <p class="mt-2 text-sm text-red-900">
                    Catatan dari admin:
                </p>
                <p class="mt-2 whitespace-pre-line rounded-lg bg-white p-3 text-sm text-gray-700">
                    {{ $anggota->catatan_verifikasi ?? 'Belum ada catatan dari admin.' }}
                </p>

                <p class="mt-3 text-sm text-red-900">
                    Silakan hubungi admin atau perbaiki data jika fitur edit sudah tersedia.
                </p>
            </div>

        @elseif ($anggota->status_verifikasi === 'approved')
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                <h2 class="text-lg font-bold text-emerald-800">
                    Pendaftaran Disetujui
                </h2>
                <p class="mt-2 text-sm text-emerald-900">
                    Pendaftaran kamu sudah disetujui. Silakan buka halaman kartu anggota.
                </p>
                <a href="{{ route('kartu.index') }}"
                   class="mt-4 inline-block rounded-lg bg-emerald-700 px-5 py-3 font-semibold text-white hover:bg-emerald-800">
                    Lihat Kartu Anggota
                </a>
            </div>
        @endif

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}"
               class="text-sm font-semibold text-emerald-700 hover:underline">
                ← Kembali ke Beranda
            </a>
        </div>
    </main>

</body>
</html>

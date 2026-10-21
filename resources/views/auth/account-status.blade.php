<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Akun — MWC NU Ajibarang</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#174A35',
                            dark: '#0E3022',
                            accent: '#B69A5A',
                            surface: '#F6F7F3',
                            border: '#E4E7DF'
                        }
                    }
                }
            }
        };
    </script>
</head>
<body class="min-h-screen bg-brand-surface font-sans text-slate-800 antialiased">
    <main class="mx-auto flex min-h-screen max-w-2xl items-center px-4 py-10 sm:px-6">
        <div class="w-full overflow-hidden rounded-3xl border border-brand-border bg-white shadow-xl">
            <div class="bg-brand px-6 py-8 text-white sm:px-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <img src="{{ asset('images/logo-nu.jpg') }}" alt="Logo NU"
                         class="h-11 w-11 rounded-full border border-white/20 object-contain">
                    <span>
                        <span class="block text-sm font-extrabold">MWC NU Ajibarang</span>
                        <span class="block text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-100">Status Akun</span>
                    </span>
                </a>

                <h1 class="mt-6 text-2xl font-black tracking-tight sm:text-3xl">
                    @if ($user->isPending())
                        Menunggu Persetujuan
                    @elseif ($user->isRejected())
                        Pendaftaran Ditolak
                    @else
                        Akun Aktif
                    @endif
                </h1>
                <p class="mt-2 text-sm text-emerald-50/80">
                    Halo, {{ $user->name }}. Berikut status akun MWC NU Ajibarang Anda.
                </p>
            </div>

            <div class="space-y-5 px-6 py-7 sm:px-8">
                @if (session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-semibold text-sky-900">
                        {{ session('info') }}
                    </div>
                @endif

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-brand-border bg-brand-surface p-4">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Email</p>
                        <p class="mt-1 break-all text-sm font-bold text-slate-800">{{ $user->email }}</p>
                    </div>
                    <div class="rounded-2xl border border-brand-border bg-brand-surface p-4">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Status</p>
                        <p class="mt-1 text-sm font-bold text-slate-800">{{ $user->statusLabel() }}</p>
                    </div>
                </div>

                @if ($user->isPending())
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                        <h2 class="text-base font-black text-amber-900">Akun sedang ditinjau admin</h2>
                        <p class="mt-2 text-sm leading-relaxed text-amber-900">
                            Anda sudah berhasil mendaftar dan login. Namun fitur anggota baru dapat digunakan
                            setelah akun disetujui oleh admin. Silakan kembali lagi nanti.
                        </p>
                    </div>
                @elseif ($user->isRejected())
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
                        <h2 class="text-base font-black text-red-900">Pendaftaran belum disetujui</h2>
                        <p class="mt-2 text-sm leading-relaxed text-red-900">
                            {{ $user->status_note ?: 'Silakan hubungi admin untuk informasi lebih lanjut.' }}
                        </p>
                    </div>
                @else
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                        <h2 class="text-base font-black text-emerald-900">Akun sudah aktif</h2>
                        <p class="mt-2 text-sm leading-relaxed text-emerald-900">
                            @if ($user->isAdmin())
                                Anda dapat membuka panel admin sesuai role <strong>{{ $user->roleLabel() }}</strong>.
                            @else
                                Anda dapat melanjutkan ke dasbor dan melengkapi data anggota.
                            @endif
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @if ($user->isAdmin())
                                <a href="{{ route('admin.index') }}"
                                   class="rounded-xl bg-brand px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-dark">
                                    Buka Panel Admin
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                   class="rounded-xl bg-brand px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-dark">
                                    Buka Dasbor
                                </a>
                            @endif
                            <a href="{{ route('home') }}"
                               class="rounded-xl border border-brand-border bg-white px-4 py-2.5 text-sm font-bold text-slate-700 hover:border-brand hover:text-brand">
                                Ke Beranda
                            </a>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-brand hover:underline">
                        ← Kembali ke Beranda
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-xs font-bold text-red-700 hover:bg-red-100">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

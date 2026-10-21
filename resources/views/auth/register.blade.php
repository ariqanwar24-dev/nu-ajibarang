<x-guest-layout
    title="Daftar Akun | MWC NU Ajibarang"
    eyebrow="Pendaftaran Akun"
    heading="Bergabung bersama kami"
    subheading="Buat akun untuk mengakses layanan anggota dan informasi MWC NU Kecamatan Ajibarang."
>
    @if (session('status'))
        <x-auth-session-status class="mb-5" :status="session('status')" />
    @endif

    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3.5 text-amber-900">
        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700" aria-hidden="true">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
            </svg>
        </span>
        <div>
            <p class="text-sm font-extrabold">Pendaftaran ditinjau admin</p>
            <p class="mt-0.5 text-xs leading-5 text-amber-900/80">
                Akun baru berstatus menunggu persetujuan. Fitur anggota aktif setelah admin menyetujui akun Anda.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700" aria-hidden="true">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01"/>
                </svg>
            </span>
            <div>
                <p class="font-extrabold">Pendaftaran belum berhasil</p>
                <p class="mt-0.5 text-xs leading-5 text-red-800/80">Periksa kembali data yang ditandai, lalu kirim ulang formulir.</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-auth.field
            name="name"
            label="Nama lengkap"
            icon="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"
            placeholder="Nama sesuai data diri"
            autocomplete="name"
            autofocus
        />

        <x-auth.field
            name="email"
            label="Alamat email"
            type="email"
            icon="M4 6h16v12H4zM4 7l8 6 8-6"
            placeholder="nama@email.com"
            autocomplete="username"
        />

        <x-auth.password
            name="password"
            label="Kata sandi"
            placeholder="Buat kata sandi yang kuat"
            autocomplete="new-password"
            hint="Minimal 8 karakter. Gunakan kombinasi huruf, angka, dan simbol."
            minlength="8"
        />

        <x-auth.password
            name="password_confirmation"
            label="Ulangi kata sandi"
            placeholder="Masukkan kembali kata sandi"
            autocomplete="new-password"
            minlength="8"
        />

        <button type="submit"
                class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-brand/20 transition hover:-translate-y-0.5 hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 active:translate-y-0">
            Buat Akun
            <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/>
            </svg>
        </button>
    </form>

    <div class="mt-6 border-t border-brand-border pt-5 text-center">
        <p class="text-xs text-brand-muted">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-extrabold text-brand transition hover:text-brand-dark hover:underline">
                Masuk di sini
            </a>
        </p>
    </div>
</x-guest-layout>

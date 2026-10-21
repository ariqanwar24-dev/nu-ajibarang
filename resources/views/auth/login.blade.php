<x-guest-layout
    title="Masuk | MWC NU Ajibarang"
    eyebrow="Selamat Datang"
    heading="Masuk ke akun Anda"
    subheading="Gunakan email dan kata sandi yang terdaftar untuk melanjutkan ke layanan MWC NU Ajibarang."
>
    @if (session('status'))
        <x-auth-session-status class="mb-5" :status="session('status')" />
    @endif

    @if ($errors->any())
        <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700" aria-hidden="true">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01"/>
                </svg>
            </span>
            <div>
                <p class="font-extrabold">Masuk belum berhasil</p>
                <p class="mt-0.5 text-xs leading-5 text-red-800/80">Periksa kembali email dan kata sandi Anda, lalu coba lagi.</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <x-auth.field
            name="email"
            label="Alamat email"
            type="email"
            icon="M4 6h16v12H4zM4 7l8 6 8-6"
            placeholder="nama@email.com"
            autocomplete="username"
            autofocus
        />

        <x-auth.password
            name="password"
            label="Kata sandi"
            placeholder="Masukkan kata sandi"
            autocomplete="current-password"
        />

        <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2.5 text-sm font-semibold text-neutral-700">
                <input id="remember_me"
                       type="checkbox"
                       name="remember"
                       value="1"
                       @checked(old('remember'))
                       class="h-4 w-4 rounded border-brand-border text-brand shadow-sm focus:ring-brand/30">
                <span>Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-xs font-bold text-brand transition hover:text-brand-dark hover:underline">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <button type="submit"
                class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand px-5 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-brand/20 transition hover:-translate-y-0.5 hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 active:translate-y-0">
            Masuk ke Akun
            <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/>
            </svg>
        </button>
    </form>

    <div class="my-6 flex items-center gap-3" aria-hidden="true">
        <span class="h-px flex-1 bg-brand-border"></span>
        <span class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-muted">Belum punya akun?</span>
        <span class="h-px flex-1 bg-brand-border"></span>
    </div>

    <a href="{{ route('register') }}"
       class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-brand-border bg-white px-5 py-3.5 text-sm font-extrabold text-brand transition hover:border-brand hover:bg-brand-surface focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">
        Buat Akun Baru
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"/>
        </svg>
    </a>

    <p class="mt-3 text-center text-[11px] leading-5 text-brand-muted">
        Akun baru akan ditinjau admin sebelum dapat digunakan.
    </p>
</x-guest-layout>

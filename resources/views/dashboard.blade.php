<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-[0.25em] text-emerald-700">MWC NU Ajibarang</span>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Dashboard Utama</h2>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white px-4 py-1.5 text-xs font-bold text-emerald-800 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Akun Terverifikasi</span>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dash-animate { animation: fadeIn .6s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .dash-card { transition: all .3s cubic-bezier(0.16, 1, 0.3, 1); }
        .dash-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -15px rgba(7, 61, 44, 0.1); }
    </style>

    <div class="min-h-screen bg-[#f8faf7] py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- WELCOME BANNER --}}
            <section class="dash-animate relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-[#064e3b] via-[#047857] to-[#059669] p-6 sm:p-8 text-white shadow-xl shadow-emerald-950/10">
                <div class="absolute -right-12 -top-12 h-56 w-56 rounded-full border-[30px] border-white/5 pointer-events-none"></div>
                <div class="absolute right-20 bottom-0 h-32 w-32 rounded-full bg-amber-300/10 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 max-w-2xl">
                    <span class="inline-block rounded-full bg-white/10 px-3.5 py-1 text-xs font-semibold text-amber-200 backdrop-blur-md">
                        Selamat Datang, {{ Auth::user()->name ?? 'Warga NU' }}!
                    </span>
                    <h1 class="mt-4 text-3xl font-black tracking-tight sm:text-4xl">
                        Portal Layanan Umat & Anggota
                    </h1>
                    <p class="mt-3 text-sm leading-relaxed text-emerald-50/80 sm:text-base">
                        Anda telah masuk ke sistem digital MWC NU Kecamatan Ajibarang. Pantau informasi kegiatan, status pengajuan kartu tanda anggota, dan berita terbaru dari sini.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-300 px-5 py-3 text-sm font-extrabold text-slate-950 shadow-lg shadow-black/10 transition hover:-translate-y-0.5 hover:bg-amber-200">
                            <span>Jelajahi Berita & Website</span> <span>→</span>
                        </a>
                    </div>
                </div>
            </section>

            {{-- QUICK INFO CARDS --}}
            <section class="dash-animate grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="dash-card rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Akun</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 font-bold">✓</span>
                    </div>
                    <p class="mt-4 text-xl font-black text-slate-900">Aktif & Terdaftar</p>
                    <p class="mt-1 text-xs text-slate-500">Akses penuh ke seluruh fitur warga.</p>
                </div>

                <a href="{{ route('berita.index') }}" class="dash-card group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80 transition hover:ring-emerald-300">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Berita &amp; Kegiatan</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700 font-bold">📰</span>
                    </div>
                    <p class="mt-4 flex items-center justify-between gap-3 text-xl font-black text-slate-900">
                        Lihat Berita
                        <span class="text-sm text-emerald-700 transition group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Baca berita terbaru dan informasi kegiatan.</p>
                </a>

                <div class="dash-card rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Organisasi</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 text-teal-700 font-bold">NU</span>
                    </div>
                    <p class="mt-4 text-xl font-black text-slate-900">MWC NU Ajibarang</p>
                    <p class="mt-1 text-xs text-slate-500">Kabupaten Banyumas, Jawa Tengah.</p>
                </div>
            </section>

            {{-- CONTENT / ACTIONS GRID --}}
            <section class="dash-animate grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-2xl bg-white p-7 shadow-sm ring-1 ring-slate-200/80 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Aktivitas Cepat</span>
                        <h3 class="mt-2 text-xl font-black text-slate-900">Apa yang ingin Anda lakukan?</h3>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                            Anda dapat kembali ke halaman utama situs untuk melihat agenda kegiatan keagamaan, pengumuman ranting, atau artikel terbaru.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-800 hover:text-emerald-600">
                            <span>Kunjungi Beranda Website Utama</span> <span>↗</span>
                        </a>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#052e22] to-[#073d2c] p-7 text-white shadow-xl flex flex-col justify-between">
                    <div class="absolute -bottom-10 -right-10 h-36 w-36 rounded-full bg-emerald-500/10 blur-xl pointer-events-none"></div>
                    <div>
                        <span class="rounded-full bg-white/10 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-amber-300">Pesan Layanan</span>
                        <h3 class="mt-3 text-xl font-black leading-snug">Khidmat Tanpa Batas untuk Umat</h3>
                        <p class="mt-2 text-xs sm:text-sm text-slate-300 leading-relaxed">
                            "Merawat jagat, membangun peradaban." Terima kasih telah menjadi bagian dari ekosistem digital MWC NU Ajibarang.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-slate-400">
                        <span>Sistem Anggota Resmi</span>
                        <span class="text-amber-300 font-bold">Aman & Terpercaya</span>
                    </div>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
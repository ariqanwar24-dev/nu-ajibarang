<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-0.5">
                <div class="inline-flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                    <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-emerald-800">MWC NU Ajibarang</span>
                </div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Pusat Kendali Admin</h2>
            </div>

            <a href="{{ route('home') }}"
               class="group relative inline-flex items-center gap-3 self-start overflow-hidden rounded-xl bg-slate-900 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-slate-900/10 transition-all duration-300 hover:bg-emerald-800 hover:shadow-emerald-950/20">
                <span class="relative z-10">Lihat Website Publik</span>
                <span class="relative z-10 transition-transform duration-300 group-hover:translate-x-1">↗</span>
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-teal-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
            </a>
        </div>
    </x-slot>

    <style>
        @keyframes customFadeUp {
            from { opacity: 0; transform: translateY(20px); filter: blur(4px); }
            to { opacity: 1; transform: translateY(0); filter: blur(0); }
        }
        .animate-fade-up { animation: customFadeUp .7s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .delay-100 { animation-delay: .08s; }
        .delay-200 { animation-delay: .16s; }
        .delay-300 { animation-delay: .24s; }

        .premium-card {
            transition: all .4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .premium-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 50px -12px rgba(7, 61, 44, 0.12);
        }
    </style>

    <div class="min-h-screen bg-[#f8faf7] pb-12 pt-6">
        <div class="mx-auto max-w-[1550px] px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-7 lg:grid-cols-[280px_minmax(0,1fr)]">

                {{-- SIDEBAR NAVIGASI --}}
                <aside class="animate-fade-up h-fit rounded-[2rem] bg-[#052e22] p-6 text-white shadow-2xl shadow-emerald-950/20 ring-1 ring-white/10">

                    <div class="flex items-center gap-3.5 border-b border-white/10 pb-6">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-amber-300 to-yellow-400 font-black text-slate-950 shadow-md shadow-amber-400/20">
                            NU
                        </div>
                        <div>
                            <h3 class="text-xs font-black tracking-widest text-amber-300">MWC NU</h3>
                            <p class="text-sm font-bold text-white">Ajibarang Workspace</p>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl bg-white/[0.04] p-4 ring-1 ring-inset ring-white/10">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300/85">Sistem Operasional</span>
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                            </span>
                        </div>
                        <p class="mt-2 text-xs font-medium text-slate-300">Database & Server Terhubung Normal.</p>
                    </div>

                    <nav class="mt-6 space-y-1.5">
                        <p class="px-3 pb-2 text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/60">Navigasi Utama</p>

                        <a href="{{ route('admin.index') }}" class="group flex items-center justify-between rounded-xl bg-gradient-to-r from-emerald-600 to-teal-700 px-3.5 py-3 text-sm font-bold text-white shadow-md shadow-emerald-900/30">
                            <div class="flex items-center gap-3">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/20 text-amber-300">⌂</span>
                                <span>Ringkasan Dashboard</span>
                            </div>
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-300"></span>
                        </a>

                        <a href="{{ route('admin.anggota.index') }}" class="group flex items-center justify-between rounded-xl px-3.5 py-3 text-sm font-medium text-slate-300 transition-all hover:bg-white/5 hover:text-white">
                            <div class="flex items-center gap-3">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-slate-400 transition-colors group-hover:bg-white/10 group-hover:text-white">♙</span>
                                <span>Verifikasi Anggota</span>
                            </div>
                            @if(($anggotaMenunggu ?? 0) > 0)
                                <span class="rounded-full bg-amber-400 px-2.5 py-0.5 text-[10px] font-black text-slate-950 shadow">{{ $anggotaMenunggu }}</span>
                            @endif
                        </a>

                        <a href="{{ route('admin.berita.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-300 transition-all hover:bg-white/5 hover:text-white">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-slate-400 transition-colors group-hover:bg-white/10 group-hover:text-white">▤</span>
                            <span>Kelola Berita</span>
                        </a>

                        <a href="{{ route('admin.pengurus.index') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-300 transition-all hover:bg-white/5 hover:text-white">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-slate-400 transition-colors group-hover:bg-white/10 group-hover:text-white">♧</span>
                            <span>Kelola Struktur Pengurus</span>
                        </a>

                        <a href="{{ route('admin.berita.create') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-300 transition-all hover:bg-white/5 hover:text-white">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-slate-400 transition-colors group-hover:bg-white/10 group-hover:text-white">✎</span>
                            <span>Tulis Berita Baru</span>
                        </a>

                        <a href="{{ route('admin.pengurus.create') }}" class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium text-slate-300 transition-all hover:bg-white/5 hover:text-white">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-slate-400 transition-colors group-hover:bg-white/10 group-hover:text-white">＋</span>
                            <span>Tambah Data Pengurus</span>
                        </a>
                    </nav>

                    <div class="mt-8 border-t border-white/10 pt-5">
                        <a href="{{ route('home') }}" class="flex items-center justify-between rounded-xl px-3 py-2 text-xs font-bold text-slate-400 transition-colors hover:text-white">
                            <span>Lihat Website Publik</span>
                            <span>↗</span>
                        </a>
                    </div>
                </aside>

                {{-- KONTEN UTAMA --}}
                <main class="min-w-0 space-y-7">

                    {{-- HERO BANNER --}}
                    <section class="animate-fade-up delay-100 relative overflow-hidden rounded-[2.25rem] bg-gradient-to-br from-[#064e3b] via-[#043c2d] to-[#022c20] p-8 text-white shadow-2xl shadow-emerald-950/15 sm:p-10 lg:p-12">
                        <div class="absolute -right-16 -top-16 h-72 w-72 rounded-full bg-emerald-500/10 blur-3xl"></div>
                        <div class="absolute right-1/3 bottom-0 h-48 w-48 rounded-full bg-amber-400/10 blur-2xl"></div>

                        <div class="relative z-10 max-w-2xl">
                            <div class="inline-flex items-center gap-2.5 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold text-emerald-200 backdrop-blur-md ring-1 ring-white/15">
                                <span class="h-2 w-2 rounded-full bg-amber-300"></span>
                                Dashboard Utama Organisasi
                            </div>

                            <h1 class="mt-5 text-3xl font-black tracking-tight text-white sm:text-5xl">
                                Selamat Datang Kembali, <br><span class="text-amber-300">Admin NU Ajibarang.</span>
                            </h1>

                            <p class="mt-4 text-sm font-medium leading-relaxed text-slate-200 sm:text-base">
                                Kendalikan alur informasi, pastikan pembaruan struktur kepengurusan berjalan lancar, dan tinjau data keanggotaan umat secara real-time dari satu tempat.
                            </p>

                            <div class="mt-8 flex flex-wrap items-center gap-3.5">
                                <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-amber-400 px-6 py-3.5 text-sm font-extrabold text-slate-950 shadow-xl shadow-amber-400/15 transition-all hover:-translate-y-0.5 hover:bg-amber-300">
                                    <span>+ Buat Berita</span>
                                </a>
                                <a href="{{ route('admin.anggota.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/10 px-6 py-3.5 text-sm font-bold text-white backdrop-blur-md ring-1 ring-white/20 transition-all hover:-translate-y-0.5 hover:bg-white/20">
                                    <span>Tinjau Verifikasi Anggota</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>

                        <div class="absolute right-8 bottom-8 hidden lg:flex items-center gap-4 rounded-2xl bg-black/20 p-4 backdrop-blur-md ring-1 ring-white/10">
                            <div class="text-right">
                                <p class="text-xs font-bold uppercase tracking-wider text-amber-300">Wilayah Kerja</p>
                                <p class="text-sm font-extrabold text-white">MWC NU Kecamatan Ajibarang</p>
                            </div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-400 text-xs font-black text-slate-950">ID</div>
                        </div>
                    </section>

                    {{-- STATISTIK --}}
                    <section class="animate-fade-up delay-200">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-extrabold text-slate-900">Ringkasan Sistem</h3>
                                <p class="text-xs text-slate-500">Statistik langsung dari database MWC NU.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

                            {{-- KARTU BERITA --}}
                            <article class="premium-card relative overflow-hidden rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
                                <div class="flex items-start justify-between">
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Berita</p>
                                        <h4 class="text-4xl font-black text-slate-900">{{ $jumlahBerita }}</h4>
                                    </div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 text-xl font-bold">▤</div>
                                </div>
                                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <span class="text-xs font-medium text-slate-500">Artikel Aktif</span>
                                    <a href="{{ route('admin.berita.index') }}" class="text-xs font-extrabold text-emerald-700 hover:text-emerald-900">Kelola →</a>
                                </div>
                            </article>

                            {{-- KARTU PENGURUS --}}
                            <article class="premium-card relative overflow-hidden rounded-[1.75rem] bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
                                <div class="flex items-start justify-between">
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Data Pengurus</p>
                                        <h4 class="text-4xl font-black text-slate-900">{{ $jumlahPengurus }}</h4>
                                    </div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-50 text-teal-700 text-xl font-bold">♧</div>
                                </div>
                                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <span class="text-xs font-medium text-slate-500">Struktur Terdaftar</span>
                                    <a href="{{ route('admin.pengurus.index') }}" class="text-xs font-extrabold text-teal-700 hover:text-teal-900">Kelola →</a>
                                </div>
                            </article>

                            {{-- KARTU VERIFIKASI --}}
                            <article class="premium-card relative overflow-hidden rounded-[1.75rem] bg-gradient-to-br from-white via-amber-50/30 to-amber-100/50 p-6 shadow-sm ring-1 ring-amber-200/80 sm:col-span-2 lg:col-span-1">
                                <div class="flex items-start justify-between">
                                    <div class="space-y-1">
                                        <p class="text-xs font-bold uppercase tracking-wider text-amber-800">Perlu Ditinjau</p>
                                        <h4 class="text-4xl font-black text-slate-900">{{ $anggotaMenunggu ?? 0 }}</h4>
                                    </div>
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-200 text-amber-900 text-xl font-bold">◷</div>
                                </div>
                                <div class="mt-6 flex items-center justify-between border-t border-amber-200/50 pt-4">
                                    <span class="text-xs font-medium text-amber-900/80">Antrean Anggota Baru</span>
                                    <a href="{{ route('admin.anggota.index') }}" class="text-xs font-extrabold text-amber-900 hover:text-slate-950">Tinjau Sekarang →</a>
                                </div>
                            </article>
                        </div>
                    </section>

                    {{-- AKSI CEPAT --}}
                    <section class="animate-fade-up delay-300 grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="rounded-[1.75rem] bg-white p-7 shadow-sm ring-1 ring-slate-200/70">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-black text-slate-900">Aksi Pintasan Cepat</h3>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">Navigasi Kilat</span>
                            </div>

                            <div class="mt-5 grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                                <a href="{{ route('admin.berita.create') }}" class="group rounded-2xl bg-slate-50 p-4 transition-all hover:bg-emerald-50/60 hover:ring-1 hover:ring-emerald-300">
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-emerald-900">Tulis Berita</p>
                                    <p class="mt-1 text-xs text-slate-500">Publikasi kegiatan terbaru organisasi.</p>
                                </a>

                                <a href="{{ route('admin.pengurus.create') }}" class="group rounded-2xl bg-slate-50 p-4 transition-all hover:bg-emerald-50/60 hover:ring-1 hover:ring-emerald-300">
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-emerald-900">Tambah Pengurus</p>
                                    <p class="mt-1 text-xs text-slate-500">Perbarui susunan kepengurusan.</p>
                                </a>

                                <a href="{{ route('admin.anggota.index') }}" class="group rounded-2xl bg-slate-50 p-4 sm:col-span-2 transition-all hover:bg-amber-50/60 hover:ring-1 hover:ring-amber-300">
                                    <p class="text-sm font-bold text-slate-900 group-hover:text-amber-900">Verifikasi Anggota (KTA)</p>
                                    <p class="mt-1 text-xs text-slate-500">Periksa keabsahan dokumen pendaftaran anggota baru.</p>
                                </a>
                            </div>
                        </div>

                        {{-- CATATAN --}}
                        <div class="relative overflow-hidden rounded-[1.75rem] bg-gradient-to-br from-slate-900 to-slate-950 p-7 text-white shadow-xl">
                            <div class="absolute -bottom-12 -right-12 h-44 w-44 rounded-full bg-emerald-500/10 blur-2xl"></div>
                            <div class="relative z-10">
                                <span class="rounded-full bg-white/10 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-amber-300">Catatan Penting</span>
                                <h3 class="mt-4 text-lg font-black leading-snug">Integritas Data dan Pelayanan Umat</h3>
                                <p class="mt-2.5 text-xs leading-relaxed text-slate-300 sm:text-sm">
                                    Pastikan setiap data yang dimasukkan akurat, dapat dipertanggungjawabkan, serta mematuhi prinsip keorganisasian Nahdlatul Ulama yang transparan dan melayani.
                                </p>
                            </div>
                            <div class="relative z-10 mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
                                <span class="text-xs text-slate-400">MWC NU Ajibarang System</span>
                                <span class="text-xs font-bold text-amber-300">Aman & Terenkripsi</span>
                            </div>
                        </div>
                    </section>

                    {{-- FOOTER --}}
                    <footer class="animate-fade-up border-t border-slate-200/80 pt-6 pb-2 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p>© {{ date('Y') }} MWC NU Kecamatan Ajibarang. Seluruh hak cipta dilindungi.</p>
                        <div class="flex items-center gap-4 text-slate-600 font-medium">
                            <span>Versi 3.0 Pro</span>
                            <span>•</span>
                            <span>Admin Workspace</span>
                        </div>
                    </footer>

                </main>
            </div>
        </div>
    </div>
</x-app-layout>
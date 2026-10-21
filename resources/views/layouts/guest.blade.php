@props([
    'title' => 'Akses Akun | MWC NU Ajibarang',
    'eyebrow' => 'Portal Layanan',
    'heading' => 'Akses Akun',
    'subheading' => 'Masuk atau daftar untuk mengakses layanan dan informasi MWC NU Ajibarang.',
])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Akses akun Portal Layanan MWC NU Kecamatan Ajibarang.">
    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#174A35',
                            dark: '#0E3022',
                            surface: '#F8F7F2',
                            accent: '#B69A5A',
                            border: '#E5E0D5',
                            muted: '#636A66'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['Newsreader', 'serif']
                    }
                }
            }
        };
    </script>

    <style>
        .auth-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.14) 1px, transparent 0);
            background-size: 22px 22px;
        }

        .auth-ring {
            background: radial-gradient(circle, rgba(182, 154, 90, 0.22) 0%, rgba(182, 154, 90, 0) 68%);
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: #171717;
            box-shadow: 0 0 0 1000px #ffffff inset;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>

<body class="min-h-screen bg-brand-surface font-sans text-neutral-900 antialiased selection:bg-brand selection:text-white">
    <div class="relative flex min-h-screen flex-col overflow-hidden">
        {{-- Ornamen latar --}}
        <div class="pointer-events-none absolute -left-28 -top-28 h-80 w-80 rounded-full bg-brand/10 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-32 -right-20 h-96 w-96 rounded-full bg-brand-accent/15 blur-3xl" aria-hidden="true"></div>

        {{-- Header --}}
        <header class="relative z-10 mx-auto flex w-full max-w-6xl items-center justify-between gap-4 px-4 py-5 sm:px-6 sm:py-6">
            <a href="{{ route('home') }}" class="group inline-flex min-w-0 items-center gap-3">
                <img src="{{ asset('images/logo-nu.jpg') }}"
                     alt="Logo NU"
                     class="h-11 w-11 shrink-0 rounded-full border border-brand-border bg-white object-contain shadow-sm">
                <span class="min-w-0">
                    <span class="block truncate text-sm font-extrabold tracking-tight text-brand transition group-hover:text-brand-dark">
                        MWC NU Ajibarang
                    </span>
                    <span class="block truncate text-[11px] text-brand-muted">
                        Kecamatan Ajibarang, Banyumas
                    </span>
                </span>
            </a>

            <a href="{{ route('home') }}"
               class="hidden items-center gap-1.5 rounded-full border border-brand-border bg-white/70 px-3.5 py-2 text-[11px] font-bold text-brand-muted backdrop-blur transition hover:border-brand hover:text-brand sm:inline-flex">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7"/>
                </svg>
                Kembali ke Beranda
            </a>
        </header>

        {{-- Konten utama --}}
        <main class="relative z-10 flex flex-1 items-center justify-center px-4 pb-10 sm:px-6 sm:pb-14">
            <div class="grid w-full max-w-5xl overflow-hidden rounded-[1.75rem] border border-brand-border bg-white shadow-[0_24px_70px_-28px_rgba(14,48,34,0.35)] lg:grid-cols-[0.94fr_1.06fr] lg:rounded-[2rem]">
                {{-- Panel identitas organisasi --}}
                <aside class="relative hidden flex-col overflow-hidden bg-brand p-9 text-white lg:flex">
                    <div class="auth-pattern absolute inset-0 opacity-60" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -right-20 top-16 h-64 w-64 rounded-full border border-white/10" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -bottom-24 -left-16 h-72 w-72 rounded-full border border-white/10" aria-hidden="true"></div>
                    <div class="auth-ring pointer-events-none absolute -bottom-24 right-0 h-72 w-72 rounded-full blur-2xl" aria-hidden="true"></div>

                    <div class="relative z-10 flex h-full flex-col">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logo-nu.jpg') }}"
                                 alt="Logo NU"
                                 class="h-12 w-12 rounded-full border border-white/20 bg-white object-contain">
                            <div>
                                <p class="text-sm font-extrabold tracking-tight">MWC NU Ajibarang</p>
                                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-100/80">
                                    Nahdlatul Ulama
                                </p>
                            </div>
                        </div>

                        <div class="my-auto py-12">
                            <p class="inline-flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.24em] text-[#D9C28D]">
                                <span class="h-px w-7 bg-[#D9C28D]" aria-hidden="true"></span>
                                Portal Layanan Digital
                            </p>

                            <h2 class="mt-4 max-w-sm font-serif text-[2.35rem] font-medium leading-[1.12] tracking-tight">
                                Merawat Tradisi,
                                <span class="italic text-[#F2E8CF]">Menguatkan Umat</span>
                            </h2>

                            <p class="mt-4 max-w-sm text-sm leading-7 text-emerald-50/75">
                                Satu pintu untuk layanan anggota, informasi kegiatan, dan administrasi
                                MWC NU Kecamatan Ajibarang.
                            </p>

                            <ul class="mt-9 space-y-4 text-sm">
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/15">
                                        <svg class="h-4 w-4 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/>
                                        </svg>
                                    </span>
                                    <span>
                                        <span class="block font-bold text-white">Layanan anggota terpadu</span>
                                        <span class="mt-0.5 block text-xs leading-5 text-emerald-50/65">Kelola data dan kartu anggota dalam satu akun.</span>
                                    </span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/15">
                                        <svg class="h-4 w-4 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2M7 16h6M7 8h6v4H7V8Z"/>
                                        </svg>
                                    </span>
                                    <span>
                                        <span class="block font-bold text-white">Berita &amp; kegiatan</span>
                                        <span class="mt-0.5 block text-xs leading-5 text-emerald-50/65">Ikuti kabar terbaru dan agenda organisasi.</span>
                                    </span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/15">
                                        <svg class="h-4 w-4 text-[#D9C28D]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 4 7v5c0 4.4 3.4 8.4 8 9 4.6-.6 8-4.6 8-9V7l-8-4Zm-2.5 9 2 2 4-4"/>
                                        </svg>
                                    </span>
                                    <span>
                                        <span class="block font-bold text-white">Akses aman &amp; terverifikasi</span>
                                        <span class="mt-0.5 block text-xs leading-5 text-emerald-50/65">Akun baru ditinjau admin sebelum digunakan.</span>
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="relative z-10 border-t border-white/10 pt-5">
                            <p class="font-serif text-sm italic leading-6 text-emerald-50/70">
                                &ldquo;Merawat jagat, membangun peradaban.&rdquo;
                            </p>
                            <p class="mt-2 text-[10px] font-semibold uppercase tracking-[0.16em] text-emerald-100/50">
                                MWC NU Kecamatan Ajibarang
                            </p>
                        </div>
                    </div>
                </aside>

                {{-- Panel formulir --}}
                <section class="flex flex-col justify-center px-5 py-8 sm:px-8 sm:py-10 lg:px-10">
                    <div class="mx-auto w-full max-w-md">
                        <div class="mb-6 flex items-center gap-3 lg:hidden">
                            <img src="{{ asset('images/logo-nu.jpg') }}"
                                 alt="Logo NU"
                                 class="h-10 w-10 rounded-full border border-brand-border bg-white object-contain">
                            <div>
                                <p class="text-xs font-extrabold tracking-tight text-brand">MWC NU Ajibarang</p>
                                <p class="text-[10px] text-brand-muted">Portal Layanan Digital</p>
                            </div>
                        </div>

                        <p class="inline-flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.24em] text-brand-accent">
                            <span class="h-px w-6 bg-brand-accent" aria-hidden="true"></span>
                            {{ $eyebrow }}
                        </p>

                        <h1 class="mt-3 font-serif text-3xl font-medium leading-tight tracking-tight text-brand sm:text-[2.1rem]">
                            {{ $heading }}
                        </h1>

                        <p class="mt-2 text-sm leading-6 text-brand-muted">
                            {{ $subheading }}
                        </p>

                        <div class="mt-7">
                            {{ $slot }}
                        </div>

                        <div class="mt-7 flex flex-wrap items-center justify-between gap-2 border-t border-brand-border pt-4 text-[11px] text-brand-muted">
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 font-bold text-brand transition hover:text-brand-dark">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7"/>
                                </svg>
                                Beranda
                            </a>
                            <span>Butuh bantuan? Hubungi admin MWC NU.</span>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <footer class="relative z-10 border-t border-brand-border/70 bg-white/40">
            <div class="mx-auto flex max-w-6xl flex-col gap-1.5 px-4 py-4 text-[10.5px] text-brand-muted sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p class="font-semibold text-brand">MWC NU Kecamatan Ajibarang</p>
                <p>&copy; {{ date('Y') }} MWC NU Ajibarang. Seluruh hak cipta dilindungi.</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-password-toggle]').forEach((button) => {
                const input = document.getElementById(button.dataset.passwordToggle);

                if (!input) {
                    return;
                }

                const iconShow = button.querySelector('[data-icon-show]');
                const iconHide = button.querySelector('[data-icon-hide]');

                button.addEventListener('click', () => {
                    const akanTampil = input.type === 'password';

                    input.type = akanTampil ? 'text' : 'password';
                    button.setAttribute('aria-pressed', akanTampil ? 'true' : 'false');
                    button.setAttribute('aria-label', akanTampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');

                    iconShow?.classList.toggle('hidden', akanTampil);
                    iconHide?.classList.toggle('hidden', ! akanTampil);

                    input.focus({ preventScroll: true });
                });
            });
        });
    </script>
</body>
</html>

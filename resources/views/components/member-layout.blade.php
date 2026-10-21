@props([
    'title' => 'Kartu Anggota — MWC NU Ajibarang',
    'eyebrow' => 'Kartu Anggota',
    'heading' => 'Kartu Anggota',
    'description' => null,
])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Pendaftaran dan status kartu anggota MWC NU Kecamatan Ajibarang.">
    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Halaman publik proyek ini memakai Tailwind melalui CDN. --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#174A35',
                            dark: '#0E3022',
                            deeper: '#08251A',
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

    @stack('styles')

    <style>
        [x-cloak] {
            display: none !important;
        }

        .member-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.14) 1px, transparent 0);
            background-size: 22px 22px;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        select:-webkit-autofill,
        select:-webkit-autofill:hover,
        select:-webkit-autofill:focus {
            -webkit-text-fill-color: #171717;
            box-shadow: 0 0 0 1000px #ffffff inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-brand-surface font-sans text-neutral-900 antialiased selection:bg-brand selection:text-white">
    <x-public-navbar />

    <main class="pb-16">
        <section class="relative isolate overflow-hidden bg-brand text-white">
            <div class="member-pattern absolute inset-0 opacity-60" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full border border-white/10" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-24 left-1/4 h-64 w-64 rounded-full bg-brand-accent/10 blur-3xl" aria-hidden="true"></div>

            <div class="relative mx-auto max-w-5xl px-4 py-9 sm:px-6 sm:py-12 lg:px-8">
                <p class="inline-flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.24em] text-[#D9C28D]">
                    <span class="h-px w-6 bg-[#D9C28D]" aria-hidden="true"></span>
                    {{ $eyebrow }}
                </p>
                <h1 class="mt-3 max-w-3xl font-serif text-3xl font-medium leading-tight tracking-tight sm:text-4xl">
                    {{ $heading }}
                </h1>
                @if (filled($description))
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-emerald-50/75 sm:text-[15px]">
                        {{ $description }}
                    </p>
                @endif
            </div>
        </section>

        {{ $slot }}
    </main>

    <footer class="border-t border-brand-border bg-white/60 no-print">
        <div class="mx-auto flex max-w-5xl flex-col gap-1.5 px-4 py-5 text-[10.5px] text-brand-muted sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p class="font-semibold text-brand">MWC NU Kecamatan Ajibarang</p>
            <p>&copy; {{ date('Y') }} MWC NU Ajibarang. Seluruh hak cipta dilindungi.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

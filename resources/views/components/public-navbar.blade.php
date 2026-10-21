@php
    $user = auth()->user();
    $navigation = [
        [
            'label' => 'Beranda',
            'url' => route('home'),
            'active' => request()->routeIs('home'),
            'icon' => 'M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5',
        ],
        [
            'label' => 'Berita',
            'url' => route('berita.index'),
            'active' => request()->routeIs('berita.*'),
            'icon' => 'M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2M7 16h6M7 8h6v4H7V8Z',
        ],
        [
            'label' => 'Struktur Organisasi',
            'url' => route('pengurus.index'),
            'active' => request()->routeIs('pengurus.*'),
            'icon' => 'M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857a5.002 5.002 0 0 0-9.288 0M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z',
        ],
        [
            'label' => 'Lacak Berita',
            'url' => route('tracking.index'),
            'active' => request()->routeIs('tracking.*'),
            'icon' => 'm21 21-4.35-4.35M17 11a6 6 0 1 1-12 0 6 6 0 0 1 12 0Z',
        ],
        [
            'label' => 'Kartu Anggota',
            'url' => route('kartu.index'),
            'active' => request()->routeIs('kartu.*'),
            'icon' => 'M10 6H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-5m-4 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1m-6 0h6',
        ],
    ];
@endphp

<header data-public-navbar class="sticky top-0 z-50 border-b border-[#E5E0D5] bg-white/95 shadow-[0_1px_0_rgba(23,74,53,0.04)] backdrop-blur-md">
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-5 lg:px-8" aria-label="Navigasi utama">
        <a href="{{ route('home') }}" class="group flex min-w-0 items-center gap-3">
            <img src="{{ asset('images/logo-nu.jpg') }}"
                 alt="Logo NU"
                 class="h-11 w-11 shrink-0 rounded-full border border-neutral-200 bg-white object-contain">

            <span class="min-w-0">
                <span class="block truncate text-sm font-extrabold tracking-tight text-[#174A35] transition-colors group-hover:text-[#113928]">
                    MWC NU Ajibarang
                </span>
                <span class="block truncate text-[11px] text-[#636A66]">
                    Banyumas, Jawa Tengah
                </span>
            </span>
        </a>

        {{-- Navigasi desktop --}}
        <div class="hidden items-center gap-1 lg:flex">
            @foreach ($navigation as $item)
                <a href="{{ $item['url'] }}"
                   @if ($item['active']) aria-current="page" @endif
                   class="whitespace-nowrap rounded-full px-3.5 py-2 text-[13px] font-semibold transition-colors lg:px-4 {{ $item['active']
                       ? 'bg-[#174A35] text-white shadow-sm'
                       : 'text-[#3F4A44] hover:bg-[#F3F6F3] hover:text-[#174A35]' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <span class="mx-2 h-7 w-px bg-[#E5E0D5]" aria-hidden="true"></span>

            @auth
                <div class="relative">
                    <button type="button"
                            data-account-toggle
                            aria-expanded="false"
                            aria-controls="public-navbar-account"
                            class="flex items-center gap-2 rounded-full border border-[#E5E0D5] bg-white p-1 pr-2.5 text-left transition hover:border-[#174A35]/40 hover:bg-[#F8F7F2]">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#174A35] text-xs font-extrabold text-white ring-2 ring-[#B69A5A]/25">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </span>
                        <span class="hidden max-w-[120px] lg:block">
                            <span class="block truncate text-[11.5px] font-bold leading-tight text-[#24352C]">{{ $user->name }}</span>
                            <span class="block truncate text-[9.5px] font-semibold uppercase tracking-wide text-[#8A918D]">{{ $user->roleLabel() }}</span>
                        </span>
                        <svg class="h-4 w-4 text-[#7A827D]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div id="public-navbar-account"
                         data-account-menu
                         class="absolute right-0 z-50 mt-2 hidden w-64 overflow-hidden rounded-2xl border border-[#E5E0D5] bg-white shadow-xl">
                        <div class="border-b border-[#EEEAE1] bg-[#F8F7F2] px-4 py-3.5">
                            <p class="truncate text-sm font-extrabold text-[#174A35]">{{ $user->name }}</p>
                            <p class="mt-0.5 truncate text-[11px] text-[#636A66]">{{ $user->email }}</p>
                            <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                <span class="rounded-full bg-[#174A35]/10 px-2 py-0.5 text-[9.5px] font-extrabold uppercase tracking-wide text-[#174A35]">
                                    {{ $user->roleLabel() }}
                                </span>
                                @if (! $user->isActive())
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[9.5px] font-extrabold uppercase tracking-wide text-amber-800">
                                        {{ $user->statusLabel() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-2">
                            @if ($user->isActive())
                                <a href="{{ route('dashboard') }}"
                                   class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs font-semibold text-[#3F4A44] transition hover:bg-[#F3F6F3] hover:text-[#174A35]">
                                    <svg class="h-4 w-4 text-[#B69A5A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-13h6V4h-6v3Z"/>
                                    </svg>
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('account.status') }}"
                                   class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs font-semibold text-amber-800 transition hover:bg-amber-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3.5m0 3h.01M10.3 4.2 2.7 17.4A1.5 1.5 0 0 0 4 19.7h16a1.5 1.5 0 0 0 1.3-2.3L13.7 4.2a2 2 0 0 0-3.4 0Z"/>
                                    </svg>
                                    Status Akun
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs font-semibold text-[#3F4A44] transition hover:bg-[#F3F6F3] hover:text-[#174A35]">
                                <svg class="h-4 w-4 text-[#B69A5A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"/>
                                </svg>
                                Profil Saya
                            </a>

                            @if ($user->isAdmin())
                                <a href="{{ route('admin.index') }}"
                                   class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs font-semibold text-[#174A35] transition hover:bg-[#F3F6F3]">
                                    <svg class="h-4 w-4 text-[#B69A5A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.3 4.2 2.7 17.4A1.5 1.5 0 0 0 4 19.7h8m2.3-15.5 7.6 13.2a1.5 1.5 0 0 1-1.3 2.3H14M9 13h6"/>
                                    </svg>
                                    Panel Admin
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-[#EEEAE1] pt-1">
                                @csrf
                                <button type="submit"
                                        class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-left text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12H3m0 0 3-3m-3 3 3 3M10 4h9a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-9"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-full border border-[#174A35] px-4 py-2 text-[13px] font-bold text-[#174A35] transition hover:bg-[#F3F6F3]">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                   class="rounded-full bg-[#174A35] px-4 py-2 text-[13px] font-bold text-white shadow-sm transition hover:bg-[#113928]">
                    Daftar
                </a>
            @endauth
        </div>

        {{-- Tombol menu mobile --}}
        <button type="button"
                data-navbar-toggle
                aria-expanded="false"
                aria-controls="public-navbar-mobile"
                aria-label="Buka menu navigasi"
                class="rounded-xl border border-[#E5E0D5] p-2 text-[#174A35] transition hover:bg-[#F3F6F3] lg:hidden">
            <svg data-navbar-icon-open class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg data-navbar-icon-close class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 18 12-12M6 6l12 12"/>
            </svg>
        </button>
    </nav>

    {{-- Menu mobile --}}
    <div id="public-navbar-mobile" data-navbar-menu class="hidden border-t border-[#E5E0D5] bg-white lg:hidden">
        <div class="mx-auto max-w-7xl px-4 py-4">
            <div class="space-y-1">
                @foreach ($navigation as $item)
                    <a href="{{ $item['url'] }}"
                       @if ($item['active']) aria-current="page" @endif
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ $item['active']
                           ? 'bg-[#174A35] text-white shadow-sm'
                           : 'text-[#3F4A44] hover:bg-[#F3F6F3] hover:text-[#174A35]' }}">
                        <svg class="h-5 w-5 {{ $item['active'] ? 'text-[#D9C28D]' : 'text-[#174A35]' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="my-3 border-t border-[#E5E0D5]"></div>

            @auth
                <div class="rounded-2xl border border-[#E5E0D5] bg-[#F8F7F2] p-3">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#174A35] text-sm font-extrabold text-white ring-2 ring-[#B69A5A]/25">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-extrabold text-[#174A35]">{{ $user->name }}</span>
                            <span class="block truncate text-[11px] text-[#636A66]">{{ $user->email }}</span>
                        </span>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-1.5">
                        <span class="rounded-full bg-white px-2 py-0.5 text-[9.5px] font-extrabold uppercase tracking-wide text-[#174A35] ring-1 ring-[#E5E0D5]">
                            {{ $user->roleLabel() }}
                        </span>
                        @if (! $user->isActive())
                            <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[9.5px] font-extrabold uppercase tracking-wide text-amber-800">
                                {{ $user->statusLabel() }}
                            </span>
                        @endif
                    </div>

                    <div class="mt-3 grid gap-1.5">
                        @if ($user->isActive())
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-2.5 rounded-xl bg-white px-3 py-2.5 text-xs font-semibold text-[#3F4A44] ring-1 ring-[#E5E0D5] transition hover:text-[#174A35]">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('account.status') }}"
                               class="flex items-center gap-2.5 rounded-xl bg-white px-3 py-2.5 text-xs font-semibold text-amber-800 ring-1 ring-amber-200 transition hover:bg-amber-50">
                                Status Akun
                            </a>
                        @endif

                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-2.5 rounded-xl bg-white px-3 py-2.5 text-xs font-semibold text-[#3F4A44] ring-1 ring-[#E5E0D5] transition hover:text-[#174A35]">
                            Profil Saya
                        </a>

                        @if ($user->isAdmin())
                            <a href="{{ route('admin.index') }}"
                               class="flex items-center gap-2.5 rounded-xl bg-white px-3 py-2.5 text-xs font-semibold text-[#174A35] ring-1 ring-[#E5E0D5] transition hover:bg-[#F3F6F3]">
                                Panel Admin
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="pt-1">
                            @csrf
                            <button type="submit"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-3 py-2.5 text-xs font-bold text-red-600 transition hover:bg-red-50">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}"
                       class="rounded-xl border border-[#174A35] px-4 py-3 text-center text-sm font-bold text-[#174A35] transition hover:bg-[#F3F6F3]">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="rounded-xl bg-[#174A35] px-4 py-3 text-center text-sm font-bold text-white shadow-sm transition hover:bg-[#113928]">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

@once
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-public-navbar]').forEach((navbar) => {
                const mobileToggle = navbar.querySelector('[data-navbar-toggle]');
                const mobileMenu = navbar.querySelector('[data-navbar-menu]');
                const iconOpen = navbar.querySelector('[data-navbar-icon-open]');
                const iconClose = navbar.querySelector('[data-navbar-icon-close]');
                const accountToggle = navbar.querySelector('[data-account-toggle]');
                const accountMenu = navbar.querySelector('[data-account-menu]');

                const setMobileMenu = (open) => {
                    if (!mobileMenu || !mobileToggle) return;

                    mobileMenu.classList.toggle('hidden', !open);
                    mobileToggle.setAttribute('aria-expanded', String(open));
                    iconOpen?.classList.toggle('hidden', open);
                    iconClose?.classList.toggle('hidden', !open);
                };

                const setAccountMenu = (open) => {
                    if (!accountMenu || !accountToggle) return;

                    accountMenu.classList.toggle('hidden', !open);
                    accountToggle.setAttribute('aria-expanded', String(open));
                };

                mobileToggle?.addEventListener('click', () => {
                    setMobileMenu(mobileMenu?.classList.contains('hidden'));
                });

                mobileMenu?.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', () => setMobileMenu(false));
                });

                accountToggle?.addEventListener('click', (event) => {
                    event.stopPropagation();
                    setAccountMenu(accountMenu?.classList.contains('hidden'));
                });

                document.addEventListener('click', (event) => {
                    if (!accountMenu || !accountToggle) return;

                    if (!accountMenu.contains(event.target) && !accountToggle.contains(event.target)) {
                        setAccountMenu(false);
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        setMobileMenu(false);
                        setAccountMenu(false);
                    }
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        setMobileMenu(false);
                    }
                });
            });
        });
    </script>
@endonce

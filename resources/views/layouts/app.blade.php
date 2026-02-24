@php($theme = request()->cookie('theme', 'light'))
@php($cartCount = app(\App\Services\CartService::class)->count())

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $theme === 'dark' ? 'dark' : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Coffee') }}</title>
        <link rel="icon" href="{{ asset('logo.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen {{ $theme === 'dark' ? 'bg-[#0b1220] text-white' : 'bg-slate-50 text-slate-900' }}">
        <header class="sticky top-0 z-40 border-b {{ $theme === 'dark' ? 'border-white/10 bg-[#0b1220]/90' : 'border-slate-900/10 bg-white/80' }} backdrop-blur">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
                <a href="{{ route('menu') }}" class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="" class="w-9 h-9 rounded-xl object-contain" />
                </a>

                <nav class="flex items-center gap-2">
                    <a
                        href="{{ route('menu') }}"
                        class="p-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                        aria-label="{{ __('messages.nav_menu') }}"
                        title="{{ __('messages.nav_menu') }}"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 12h7V3H3v9Z" />
                            <path d="M14 21h7v-7h-7v7Z" />
                            <path d="M14 10h7V3h-7v7Z" />
                            <path d="M3 21h7v-7H3v7Z" />
                        </svg>
                    </a>

                    <a
                        href="{{ route('cart.index') }}"
                        class="relative p-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                        aria-label="{{ __('messages.nav_cart') }}"
                        title="{{ __('messages.nav_cart') }}"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 7h15l-1.5 8.5a2 2 0 0 1-2 1.5H8a2 2 0 0 1-2-1.6L4 3H2" />
                            <path d="M9 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                            <path d="M18 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                        </svg>
                        <span id="cart-count-badge" class="absolute -top-1 -right-1 min-w-5 h-5 px-1 inline-flex items-center justify-center text-[11px] rounded-full {{ $theme === 'dark' ? 'bg-amber-300/20 border border-amber-200/30 text-amber-100' : 'bg-amber-200 border border-amber-300 text-amber-900' }}">
                            {{ (int) $cartCount }}
                        </span>
                    </a>

                    <a
                        href="{{ route('lang.switch', ['locale' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}"
                        class="p-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                        aria-label="{{ __('messages.nav_language') }}"
                        title="{{ __('messages.nav_language') }}"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Z" />
                            <path d="M2 12h20" />
                            <path d="M12 2c3 3 3 17 0 20" />
                            <path d="M12 2c-3 3-3 17 0 20" />
                        </svg>
                    </a>

                    <a
                        href="{{ route('theme.switch', ['mode' => $theme === 'dark' ? 'light' : 'dark']) }}"
                        class="p-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                        aria-label="{{ $theme === 'dark' ? __('messages.nav_theme_light') : __('messages.nav_theme_dark') }}"
                        title="{{ $theme === 'dark' ? __('messages.nav_theme_light') : __('messages.nav_theme_dark') }}"
                    >
                        @if ($theme === 'dark')
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z" />
                                <path d="M12 2v2" />
                                <path d="M12 20v2" />
                                <path d="M4.93 4.93l1.41 1.41" />
                                <path d="M17.66 17.66l1.41 1.41" />
                                <path d="M2 12h2" />
                                <path d="M20 12h2" />
                                <path d="M4.93 19.07l1.41-1.41" />
                                <path d="M17.66 6.34l1.41-1.41" />
                            </svg>
                        @else
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 12.8A8.5 8.5 0 0 1 11.2 3 7 7 0 1 0 21 12.8Z" />
                            </svg>
                        @endif
                    </a>

                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="p-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                                aria-label="{{ __('messages.nav_logout') }}"
                                title="{{ __('messages.nav_logout') }}"
                            >
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <path d="M16 17l5-5-5-5" />
                                    <path d="M21 12H9" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="p-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                            aria-label="{{ __('messages.nav_login') }}"
                            title="{{ __('messages.nav_login') }}"
                        >
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                <path d="M10 17l5-5-5-5" />
                                <path d="M15 12H3" />
                            </svg>
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="p-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                            aria-label="{{ __('messages.nav_register') }}"
                            title="{{ __('messages.nav_register') }}"
                        >
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <path d="M8.5 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                                <path d="M20 8v6" />
                                <path d="M23 11h-6" />
                            </svg>
                        </a>
                    @endauth
                </nav>
            </div>
        </header>

        @isset($header)
            <div class="max-w-6xl mx-auto px-4 py-6">
                {{ $header }}
            </div>
        @endisset

        <main class="max-w-6xl mx-auto px-4 py-6">
            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </main>

        <script>
            (function () {
                function getCsrf() {
                    const meta = document.querySelector('meta[name="csrf-token"]');
                    return meta ? meta.getAttribute('content') : '';
                }

                async function handleCartFormSubmit(form) {
                    const url = form.getAttribute('action');
                    const method = (form.getAttribute('method') || 'POST').toUpperCase();
                    const formData = new FormData(form);

                    const res = await fetch(url, {
                        method,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': getCsrf(),
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    const data = await res.json();

                    if (data && typeof data.count !== 'undefined') {
                        const badge = document.getElementById('cart-count-badge');
                        if (badge) badge.textContent = String(data.count);
                    }

                    if (data && data.html) {
                        const container = document.getElementById('cart-content');
                        if (container) container.innerHTML = data.html;
                    }
                }

                document.addEventListener('submit', function (e) {
                    const form = e.target;
                    if (!(form instanceof HTMLFormElement)) return;
                    if (!form.hasAttribute('data-cart-ajax')) return;

                    e.preventDefault();
                    handleCartFormSubmit(form).catch(function () {
                        form.submit();
                    });
                });

                document.addEventListener('click', function (e) {
                    const card = e.target.closest('[data-href]');
                    if (!card) return;
                    if (e.target.closest('button, a, form, input, select, textarea, label')) return;
                    const href = card.getAttribute('data-href');
                    if (href) window.location.href = href;
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key !== 'Enter' && e.key !== ' ') return;
                    const card = e.target.closest('[data-href]');
                    if (!card) return;
                    const href = card.getAttribute('data-href');
                    if (!href) return;
                    e.preventDefault();
                    window.location.href = href;
                });
            })();
        </script>
    </body>
</html>

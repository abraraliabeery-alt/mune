@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex flex-col lg:flex-row gap-4">
        <aside class="lg:w-64">
            <div class="rounded-2xl p-4 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs tracking-widest opacity-70">{{ __('messages.nav_menu') }}</div>
                        <h1 class="text-lg font-semibold tracking-wide">{{ __('messages.menu_title') }}</h1>
                    </div>
                    @if (auth()->check() && in_array((string) auth()->user()->role, ['admin', 'staff'], true))
                        <a href="{{ route('products.index') }}" class="text-xs px-3 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">{{ __('messages.menu_manage_products') }}</a>
                    @endif
                </div>

                <div class="mt-4 space-y-2">
                    <a href="{{ route('menu') }}"
                       class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition {{ empty($activeCategory) ? ($theme === 'dark' ? 'bg-white/10 border border-white/15' : 'bg-white/80 border border-slate-900/10') : ($theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70') }}">
                        <span>{{ __('messages.menu_all') }}</span>
                        <span class="text-xs opacity-70">{{ $products->count() }}</span>
                    </a>

                    @foreach ($categories as $category)
                        @php($key = \Illuminate\Support\Str::lower($category))
                        <a href="{{ route('menu', ['category' => $category]) }}"
                           class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition {{ $activeCategory === $category ? ($theme === 'dark' ? 'bg-white/10 border border-white/15' : 'bg-white/80 border border-slate-900/10') : ($theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70') }}">
                            <span>{{ __('messages.category_' . $key) }}</span>
                            <span class="text-xs opacity-70">›</span>
                        </a>
                    @endforeach
                </div>

                <div class="mt-5">
                    <div class="text-xs opacity-70">{{ __('messages.menu_subtitle') }}</div>
                </div>
            </div>
        </aside>

        <section class="flex-1">
            <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
                <div class="flex items-center justify-between">
                    <div class="text-sm tracking-wide opacity-80">
                        {{ __('messages.nav_menu') }}
                        <span class="opacity-60">|</span>
                        {{ $activeCategory ? __('messages.category_' . \Illuminate\Support\Str::lower($activeCategory)) : __('messages.menu_all') }}
                    </div>
                    <div class="hidden sm:flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full {{ $theme === 'dark' ? 'bg-amber-300/70' : 'bg-amber-500/70' }}"></div>
                        <div class="w-8 h-1 rounded-full {{ $theme === 'dark' ? 'bg-white/15' : 'bg-slate-900/10' }}"></div>
                        <div class="w-2 h-2 rounded-full {{ $theme === 'dark' ? 'bg-white/10' : 'bg-slate-900/10' }}"></div>
                    </div>
                </div>

                <div class="mt-4">
                    @if ($products->isEmpty())
                        <div class="rounded-2xl p-6 {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}">
                            <div class="font-semibold mb-1">{{ __('messages.menu_no_products_title') }}</div>
                            <div class="text-sm opacity-75">{{ __('messages.menu_no_products_subtitle') }}</div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach ($products as $product)
                                @php($catKey = \Illuminate\Support\Str::lower($product->category))

                                <div
                                    class="group card rounded-2xl overflow-hidden transition transform hover:-translate-y-0.5 cursor-pointer {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 hover:bg-white/7' : 'bg-white/75 border border-slate-900/10 hover:bg-white/90' }}"
                                    data-href="{{ route('products.show', $product) }}"
                                    role="link"
                                    tabindex="0"
                                >
                                    <div class="relative">
                                        <div class="h-36 flex items-center justify-center">
                                            @if ($product->image_url)
                                                <img src="{{ $product->image_url }}" alt="" class="h-28 object-contain drop-shadow" />
                                            @else
                                                <div class="w-24 h-24 rounded-2xl flex items-center justify-center {{ $theme === 'dark' ? 'bg-amber-300/10 border border-amber-300/20' : 'bg-amber-200/40 border border-amber-500/20' }}">
                                                    <span class="text-2xl font-semibold {{ $theme === 'dark' ? 'text-amber-200/80' : 'text-amber-700/70' }}">☕</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="absolute inset-x-0 bottom-0 h-px {{ $theme === 'dark' ? 'hairline' : 'hairline-light' }}"></div>
                                    </div>

                                    <div class="p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-sm font-semibold tracking-wide">{{ $product->displayName() }}</div>
                                                <div class="text-xs opacity-70">{{ __('messages.category_' . $catKey) }}</div>
                                            </div>
                                            <div class="text-sm font-semibold tracking-wider">{{ number_format((float) $product->price, 2) }}</div>
                                        </div>

                                        @php($desc = $product->displayDescription())
                                        @if ($desc)
                                            <div class="mt-3 text-xs opacity-75 line-clamp-2">{{ $desc }}</div>
                                        @endif

                                        <div class="mt-4 flex items-center justify-between">
                                            <span class="text-xs px-2 py-1 rounded-xl {{ $theme === 'dark' ? 'bg-emerald-400/10 border border-emerald-300/20 text-emerald-100' : 'bg-emerald-50 border border-emerald-200 text-emerald-800' }}">{{ __('messages.menu_available') }}</span>

                                            <div class="flex items-center gap-2">
                                                <form method="POST" action="{{ route('cart.add') }}" data-cart-ajax>
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ (int) $product->id }}" />
                                                    <button
                                                        type="submit"
                                                        class="w-9 h-9 inline-flex items-center justify-center rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                                                        aria-label="{{ __('messages.menu_add_to_cart') }}"
                                                        title="{{ __('messages.menu_add_to_cart') }}"
                                                    >
                                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                            <path d="M6 7h15l-1.5 8.5a2 2 0 0 1-2 1.5H8a2 2 0 0 1-2-1.6L4 3H2" />
                                                            <path d="M9 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                                                            <path d="M18 21a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                                                            <path d="M12 9v6" />
                                                            <path d="M9 12h6" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.cart_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.cart_subtitle') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('menu') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">{{ __('messages.cart_continue') }}</a>

            @if (!empty($items))
                <form method="POST" action="{{ route('cart.clear') }}" data-cart-ajax>
                    @csrf
                    <button
                        type="submit"
                        class="w-10 h-10 inline-flex items-center justify-center rounded-xl {{ $theme === 'dark' ? 'bg-red-500/10 border border-red-500/30 hover:bg-red-500/15' : 'bg-red-50 border border-red-200 hover:bg-red-100' }}"
                        aria-label="{{ __('messages.cart_clear') }}"
                        title="{{ __('messages.cart_clear') }}"
                    >
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 6h18" />
                            <path d="M8 6V4h8v2" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                            <path d="M10 11v6" />
                            <path d="M14 11v6" />
                        </svg>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        <div id="cart-content">
            @include('cart._content', ['items' => $items, 'subtotal' => $subtotal, 'count' => $count, 'tableNumber' => $tableNumber])
        </div>
    </div>
@endsection

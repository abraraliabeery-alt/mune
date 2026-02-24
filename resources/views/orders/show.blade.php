@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.orders_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.orders_subtitle', ['code' => (string) $order->public_code]) }}</p>
        </div>

        <a href="{{ route('menu') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
            {{ __('messages.cart_continue') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            @if ((string) $order->delivery_method === 'delivery')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm opacity-70">{{ __('messages.orders_delivery_name') }}</div>
                        <div class="mt-1 font-semibold">{{ (string) $order->customer_name }}</div>
                    </div>
                    <div>
                        <div class="text-sm opacity-70">{{ __('messages.orders_delivery_phone') }}</div>
                        <div class="mt-1 font-semibold">{{ (string) $order->phone }}</div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-sm opacity-70">{{ __('messages.orders_delivery_address') }}</div>
                    <div class="mt-1 text-sm">{{ (string) $order->location_url }}</div>

                    @if (!empty($order->location_url) && filter_var((string) $order->location_url, FILTER_VALIDATE_URL))
                        <a href="{{ (string) $order->location_url }}" target="_blank" rel="noreferrer" class="mt-2 inline-flex items-center gap-2 text-sm underline">
                            {{ __('messages.orders_open_location') }}
                        </a>
                    @endif
                </div>
            @else
                <div class="flex items-center justify-between">
                    <div class="text-sm opacity-70">{{ __('messages.orders_table') }}</div>
                    <div class="text-lg font-semibold">{{ (string) ($order->table_number ?? '-') }}</div>
                </div>
            @endif

            <div class="mt-3 flex items-center justify-between">
                <div class="text-sm opacity-70">{{ __('messages.orders_status') }}</div>
                <div class="text-sm font-semibold">{{ __('messages.orders_status_' . (string) $order->status) }}</div>
            </div>

            @if (!empty($order->notes))
                <div class="mt-4">
                    <div class="text-sm opacity-70">{{ __('messages.orders_notes') }}</div>
                    <div class="mt-1 text-sm">{{ $order->notes }}</div>
                </div>
            @endif

            <div class="mt-5 border-t {{ $theme === 'dark' ? 'border-white/10' : 'border-slate-900/10' }} pt-4">
                <div class="text-sm font-semibold mb-2">{{ __('messages.orders_items') }}</div>

                <div class="space-y-2">
                    @foreach ($order->items as $item)
                        <div class="flex items-start justify-between gap-3 rounded-xl p-3 {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}">
                            <div>
                                <div class="text-sm font-semibold">{{ $item->product_name }}</div>
                                <div class="text-xs opacity-70">{{ __('messages.orders_qty', ['qty' => (int) $item->qty]) }}</div>
                            </div>
                            <div class="text-sm font-semibold tracking-wider">{{ number_format((float) $item->line_total, 2) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside class="lg:col-span-1">
            <div class="rounded-2xl p-4 {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/75 border border-slate-900/10' }}">
                <div class="flex items-center justify-between">
                    <div class="text-sm opacity-70">{{ __('messages.cart_subtotal') }}</div>
                    <div class="text-lg font-semibold tracking-wider">{{ number_format((float) $order->subtotal, 2) }}</div>
                </div>
            </div>
        </aside>
    </div>
@endsection

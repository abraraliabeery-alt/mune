@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_dashboard_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_dashboard_subtitle') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('products.index') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_dashboard_products') }}
            </a>
            <a href="{{ route('products.create') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_dashboard_add_product') }}
            </a>
            <a href="{{ route('admin.orders.index') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_dashboard_orders') }}
            </a>
            <a href="{{ route('menu') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.nav_menu') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="text-sm opacity-70">{{ __('messages.admin_dashboard_total_orders') }}</div>
            <div class="mt-2 text-3xl font-semibold tracking-wide">{{ (int) $totalOrders }}</div>
            <div class="mt-2 text-xs opacity-70">{{ __('messages.admin_dashboard_today_orders', ['count' => (int) $todayOrders]) }}</div>
        </div>

        <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="text-sm opacity-70">{{ __('messages.admin_dashboard_total_revenue') }}</div>
            <div class="mt-2 text-3xl font-semibold tracking-wide">{{ number_format((float) $totalRevenue, 2) }}</div>
            <div class="mt-2 text-xs opacity-70">{{ __('messages.admin_dashboard_today_revenue', ['amount' => number_format((float) $todayRevenue, 2)]) }}</div>
        </div>

        <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="text-sm opacity-70">{{ __('messages.admin_dashboard_products_total') }}</div>
            <div class="mt-2 text-3xl font-semibold tracking-wide">{{ (int) $productsTotal }}</div>
            <div class="mt-2 text-xs opacity-70">{{ __('messages.admin_dashboard_products_available', ['count' => (int) $productsAvailable]) }}</div>
        </div>

        <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="text-sm opacity-70">{{ __('messages.admin_dashboard_staff') }}</div>
            <div class="mt-2 text-lg font-semibold">{{ auth()->user()->name }}</div>
            <div class="mt-1 text-xs opacity-70">{{ __('messages.admin_dashboard_role', ['role' => (string) auth()->user()->role]) }}</div>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="flex items-center justify-between">
                <div class="text-sm font-semibold">{{ __('messages.admin_dashboard_recent_orders') }}</div>
                <a href="{{ route('admin.orders.index') }}" class="text-sm underline">{{ __('messages.admin_dashboard_view_all') }}</a>
            </div>

            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-left opacity-70">
                        <tr>
                            <th class="py-2 pe-3">{{ __('messages.admin_orders_code') }}</th>
                            <th class="py-2 pe-3">{{ __('messages.orders_type') }}</th>
                            <th class="py-2 pe-3">{{ __('messages.orders_status') }}</th>
                            <th class="py-2 pe-3">{{ __('messages.admin_orders_items') }}</th>
                            <th class="py-2">{{ __('messages.cart_subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr class="border-t {{ $theme === 'dark' ? 'border-white/10' : 'border-slate-900/10' }}">
                                <td class="py-3 pe-3 font-semibold">
                                    <a class="underline" href="{{ route('admin.orders.show', ['order' => $order->id]) }}">{{ $order->public_code }}</a>
                                </td>
                                <td class="py-3 pe-3">
                                    {{ (string) $order->delivery_method === 'delivery' ? __('messages.orders_type_delivery') : __('messages.orders_type_table') }}
                                </td>
                                <td class="py-3 pe-3">{{ __('messages.orders_status_' . (string) $order->status) }}</td>
                                <td class="py-3 pe-3">{{ (int) $order->items_count }}</td>
                                <td class="py-3 font-semibold">{{ number_format((float) $order->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="text-sm font-semibold mb-3">{{ __('messages.admin_dashboard_by_status') }}</div>

            @php($statuses = ['new','preparing','ready','delivered','cancelled'])

            <div class="flex flex-wrap gap-2">
                @foreach ($statuses as $st)
                    @php($count = (int) ($countsByStatus[$st] ?? 0))
                    <a href="{{ route('admin.orders.index', ['status' => $st]) }}"
                       class="px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                        {{ __('messages.orders_status_' . $st) }}: {{ $count }}
                    </a>
                @endforeach
            </div>

            <div class="mt-4 text-xs opacity-70">{{ __('messages.admin_dashboard_products_unavailable', ['count' => (int) $productsUnavailable]) }}</div>
        </div>
    </div>
@endsection

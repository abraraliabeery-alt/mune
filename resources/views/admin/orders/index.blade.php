@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_orders_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_orders_subtitle') }}</p>
        </div>

        <a href="{{ route('menu') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
            {{ __('messages.nav_menu') }}
        </a>
    </div>

    <div class="mb-4 grid grid-cols-1 lg:grid-cols-3 gap-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="lg:col-span-2">
            <input
                name="q"
                value="{{ (string) ($q ?? '') }}"
                placeholder="{{ __('messages.admin_orders_search_placeholder') }}"
                class="w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
            />
            @if (!empty($status))
                <input type="hidden" name="status" value="{{ (string) $status }}" />
            @endif
            @if (!empty($type))
                <input type="hidden" name="type" value="{{ (string) $type }}" />
            @endif
        </form>

        <div class="flex flex-wrap items-center gap-2">
            @php($types = ['' => __('messages.admin_orders_type_all'), 'table' => __('messages.orders_type_table'), 'delivery' => __('messages.orders_type_delivery')])

            @foreach ($types as $key => $label)
                <a
                    href="{{ $key === '' ? route('admin.orders.index', array_filter(['status' => $status ?? null, 'q' => $q ?? null])) : route('admin.orders.index', array_filter(['status' => $status ?? null, 'q' => $q ?? null, 'type' => $key])) }}"
                    class="px-3 py-2 rounded-xl text-sm {{ ($type ?? '') === $key ? ($theme === 'dark' ? 'chip bg-white/10' : 'chip-light bg-white') : ($theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70') }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="mb-4 flex flex-wrap items-center gap-2">
        @php($statuses = ['' => __('messages.admin_orders_filter_all'), 'new' => __('messages.orders_status_new'), 'preparing' => __('messages.orders_status_preparing'), 'ready' => __('messages.orders_status_ready'), 'delivered' => __('messages.orders_status_delivered'), 'cancelled' => __('messages.orders_status_cancelled')])

        @foreach ($statuses as $key => $label)
            <a
                href="{{ $key === '' ? route('admin.orders.index', array_filter(['type' => $type ?? null, 'q' => $q ?? null])) : route('admin.orders.index', array_filter(['status' => $key, 'type' => $type ?? null, 'q' => $q ?? null])) }}"
                class="px-3 py-2 rounded-xl text-sm {{ ($status ?? '') === $key ? ($theme === 'dark' ? 'chip bg-white/10' : 'chip-light bg-white') : ($theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70') }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left opacity-70">
                    <tr>
                        <th class="py-2 pe-3">{{ __('messages.admin_orders_code') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.orders_type') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.orders_table') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.orders_status') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_orders_items') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.cart_subtotal') }}</th>
                        <th class="py-2">{{ __('messages.admin_orders_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-t {{ $theme === 'dark' ? 'border-white/10' : 'border-slate-900/10' }}">
                            <td class="py-3 pe-3 font-semibold">{{ $order->public_code }}</td>
                            <td class="py-3 pe-3">{{ (string) $order->delivery_method === 'delivery' ? __('messages.orders_type_delivery') : __('messages.orders_type_table') }}</td>
                            <td class="py-3 pe-3">{{ $order->table_number ?? '-' }}</td>
                            <td class="py-3 pe-3">{{ __('messages.orders_status_' . (string) $order->status) }}</td>
                            <td class="py-3 pe-3">{{ (int) $order->items_count }}</td>
                            <td class="py-3 pe-3 font-semibold">{{ number_format((float) $order->subtotal, 2) }}</td>
                            <td class="py-3">
                                <a href="{{ route('admin.orders.show', ['order' => $order->id]) }}" class="px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                                    {{ __('messages.admin_orders_view') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection

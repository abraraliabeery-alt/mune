@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_dashboard_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_dashboard_subtitle') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_dashboard_orders') }}
            </a>
            <a href="{{ route('menu') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.nav_menu') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="text-sm opacity-70">{{ __('messages.admin_dashboard_total_orders') }}</div>
            <div class="mt-2 text-3xl font-semibold tracking-wide">{{ (int) $totalOrders }}</div>
        </div>

        <div class="lg:col-span-2 rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
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
        </div>
    </div>
@endsection

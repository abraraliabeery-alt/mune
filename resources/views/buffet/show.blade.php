@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.buffet_track_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.buffet_track_subtitle', ['code' => $buffet->public_code]) }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('buffet.create') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.buffet_new_request') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="text-sm font-semibold mb-2">{{ __('messages.buffet_request_info') }}</div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div><span class="opacity-70">{{ __('messages.form_name') }}:</span> {{ $buffet->customer_name ?: '—' }}</div>
                <div><span class="opacity-70">{{ __('messages.orders_delivery_phone') }}:</span> {{ $buffet->phone }}</div>
                <div><span class="opacity-70">{{ __('messages.buffet_company_name') }}:</span> {{ $buffet->company_name ?: '—' }}</div>
                <div><span class="opacity-70">{{ __('messages.buffet_people_count') }}:</span> {{ $buffet->people_count ? (int) $buffet->people_count : '—' }}</div>
                <div class="sm:col-span-2"><span class="opacity-70">{{ __('messages.buffet_event_at') }}:</span> {{ $buffet->event_at?->format('Y-m-d H:i') ?: '—' }}</div>
            </div>

            @if ($buffet->details)
                <div class="mt-3 text-sm">
                    <div class="opacity-70 mb-1">{{ __('messages.buffet_details') }}</div>
                    <div class="whitespace-pre-wrap">{{ $buffet->details }}</div>
                </div>
            @endif

            <div class="mt-4 text-xs opacity-60">{{ __('messages.buffet_status', ['status' => __('messages.buffet_status_' . (string) $buffet->status)]) }}</div>

            @if ($buffet->createdBy)
                <div class="mt-2 text-xs opacity-60">{{ __('messages.buffet_created_by_staff', ['name' => $buffet->createdBy->name]) }}</div>
            @endif
        </div>

        <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="text-sm font-semibold mb-2">{{ __('messages.buffet_quote_title') }}</div>

            @if ($buffet->quoted_at)
                <div class="text-xs opacity-70 mb-2">{{ __('messages.buffet_quoted_at', ['date' => $buffet->quoted_at->format('Y-m-d H:i')]) }}</div>
            @endif

            @if ($buffet->quote_amount !== null)
                <div class="text-2xl font-semibold tracking-wide">{{ number_format((float) $buffet->quote_amount, 2) }}</div>
            @endif

            @if ($buffet->quote_message)
                <div class="mt-2 text-sm whitespace-pre-wrap">{{ $buffet->quote_message }}</div>
            @endif

            @if (!$buffet->quote_message && $buffet->quote_amount === null)
                <div class="text-sm opacity-70">{{ __('messages.buffet_quote_pending') }}</div>
            @endif
        </div>
    </div>
@endsection

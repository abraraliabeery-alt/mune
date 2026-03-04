@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_buffet_show_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_buffet_show_subtitle', ['code' => $buffet->public_code]) }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.buffet.index') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_buffet_back') }}
            </a>
            <a href="{{ route('buffet.show', ['publicCode' => $buffet->public_code]) }}" target="_blank" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_buffet_view_public') }}
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-2xl p-4 {{ $theme === 'dark' ? 'bg-emerald-400/10 border border-emerald-300/20 text-emerald-100' : 'bg-emerald-50 border border-emerald-200 text-emerald-900' }}">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="text-sm font-semibold mb-2">{{ __('messages.buffet_request_info') }}</div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div><span class="opacity-70">{{ __('messages.form_name') }}:</span> {{ $buffet->customer_name ?: '—' }}</div>
                <div><span class="opacity-70">{{ __('messages.orders_delivery_phone') }}:</span> {{ $buffet->phone }}</div>
                <div><span class="opacity-70">{{ __('messages.buffet_company_name') }}:</span> {{ $buffet->company_name ?: '—' }}</div>
                <div><span class="opacity-70">{{ __('messages.buffet_people_count') }}:</span> {{ $buffet->people_count ? (int) $buffet->people_count : '—' }}</div>
                <div class="sm:col-span-2"><span class="opacity-70">{{ __('messages.buffet_event_at') }}:</span> {{ $buffet->event_at?->format('Y-m-d H:i') ?: '—' }}</div>
                <div><span class="opacity-70">{{ __('messages.admin_created_by') }}:</span> {{ $buffet->createdBy?->name ?? '—' }}</div>
                <div><span class="opacity-70">{{ __('messages.admin_updated_by') }}:</span> {{ $buffet->updatedBy?->name ?? '—' }}</div>
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
            <div class="text-sm font-semibold mb-2">{{ __('messages.admin_buffet_actions') }}</div>

            <form method="POST" action="{{ route('admin.buffet.status', ['buffet' => $buffet->id]) }}" class="mt-3">
                @csrf
                @method('PATCH')

                @php($statuses = ['new','contacted','quoted','confirmed','closed','cancelled'])

                <label class="text-xs opacity-70">{{ __('messages.admin_buffet_status') }}</label>
                <select name="status" class="mt-1 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">
                    @foreach ($statuses as $st)
                        <option value="{{ $st }}" @selected((string) $buffet->status === $st)>{{ __('messages.buffet_status_' . $st) }}</option>
                    @endforeach
                </select>

                <button type="submit" class="mt-2 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                    {{ __('messages.admin_buffet_save') }}
                </button>
            </form>

            <form method="POST" action="{{ route('admin.buffet.quote', ['buffet' => $buffet->id]) }}" class="mt-4">
                @csrf
                @method('PATCH')

                <label class="text-xs opacity-70">{{ __('messages.admin_buffet_quote_amount') }}</label>
                <input name="quote_amount" value="{{ old('quote_amount', $buffet->quote_amount) }}" inputmode="decimal" class="mt-1 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}" />

                <label class="mt-3 text-xs opacity-70">{{ __('messages.admin_buffet_quote_message') }}</label>
                <textarea name="quote_message" rows="4" class="mt-1 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">{{ old('quote_message', $buffet->quote_message) }}</textarea>

                <button type="submit" class="mt-2 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-amber-300/15 border border-amber-200/25 hover:bg-amber-300/20' : 'bg-amber-200/60 border border-amber-500/20 hover:bg-amber-200/80' }}">
                    {{ __('messages.admin_buffet_save_quote') }}
                </button>

                <div class="mt-2 text-xs opacity-60">{{ __('messages.admin_buffet_quote_hint') }}</div>
            </form>
        </div>
    </div>
@endsection

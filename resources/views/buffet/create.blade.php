@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.buffet_request_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.buffet_request_subtitle') }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-2xl p-4 {{ $theme === 'dark' ? 'bg-red-500/10 border border-red-500/30' : 'bg-red-50 border border-red-200' }}">
            <div class="text-sm font-semibold mb-2">{{ __('messages.errors_fix') }}</div>
            <ul class="text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('buffet.store') }}" class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_name') }}</label>
                <input name="customer_name" value="{{ old('customer_name') }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.orders_delivery_phone') }}</label>
                <input name="phone" value="{{ old('phone') }}" inputmode="tel" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.buffet_company_name') }}</label>
                <input name="company_name" value="{{ old('company_name') }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.buffet_people_count') }}</label>
                <input name="people_count" type="number" min="1" max="5000" value="{{ old('people_count') }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.buffet_event_at') }}</label>
                <input name="event_at" type="datetime-local" value="{{ old('event_at') }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}" />
            </div>
        </div>

        <div class="mt-4">
            <label class="text-xs tracking-widest opacity-70">{{ __('messages.buffet_details') }}</label>
            <textarea name="details" rows="4" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}">{{ old('details') }}</textarea>
        </div>

        <div class="mt-5 flex items-center justify-end">
            <button type="submit" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'bg-amber-300/15 border border-amber-200/25 hover:bg-amber-300/20' : 'bg-amber-200/60 border border-amber-500/20 hover:bg-amber-200/80' }}">
                {{ __('messages.buffet_submit') }}
            </button>
        </div>
    </form>
@endsection

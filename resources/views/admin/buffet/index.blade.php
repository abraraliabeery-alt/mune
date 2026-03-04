@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_buffet_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_buffet_subtitle') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_staff_back_dashboard') }}
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-2xl p-4 {{ $theme === 'dark' ? 'bg-emerald-400/10 border border-emerald-300/20 text-emerald-100' : 'bg-emerald-50 border border-emerald-200 text-emerald-900' }}">
            {{ session('status') }}
        </div>
    @endif

    @php($statuses = ['new','contacted','quoted','confirmed','closed','cancelled'])

    <div class="mb-4 grid grid-cols-1 lg:grid-cols-4 gap-3">
        <form method="GET" action="{{ route('admin.buffet.index') }}" class="lg:col-span-2">
            <input
                name="q"
                value="{{ (string) ($q ?? '') }}"
                placeholder="{{ __('messages.admin_buffet_search_placeholder') }}"
                class="w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
            />
        </form>

        <form method="GET" action="{{ route('admin.buffet.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="q" value="{{ (string) ($q ?? '') }}" />
            <select name="status" class="w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">
                <option value="">{{ __('messages.admin_buffet_status_all') }}</option>
                @foreach ($statuses as $st)
                    <option value="{{ $st }}" @selected((string) ($status ?? '') === $st)>{{ __('messages.buffet_status_' . $st) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_buffet_filter') }}
            </button>
        </form>
    </div>

    <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left opacity-70">
                    <tr>
                        <th class="py-2 pe-3">{{ __('messages.admin_buffet_code') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_buffet_phone') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_buffet_people_count') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_buffet_status') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_buffet_created_by') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_updated_by') }}</th>
                        <th class="py-2">{{ __('messages.admin_buffet_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $r)
                        <tr class="border-t {{ $theme === 'dark' ? 'border-white/10' : 'border-slate-900/10' }}">
                            <td class="py-3 pe-3 font-semibold">{{ $r->public_code }}</td>
                            <td class="py-3 pe-3">{{ $r->phone }}</td>
                            <td class="py-3 pe-3">{{ $r->people_count ? (int) $r->people_count : '—' }}</td>
                            <td class="py-3 pe-3">{{ __('messages.buffet_status_' . (string) $r->status) }}</td>
                            <td class="py-3 pe-3">
                                @if ($r->createdBy)
                                    <span class="text-xs px-2 py-1 rounded-xl {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}">
                                        {{ __('messages.admin_buffet_created_by_staff', ['name' => $r->createdBy->name]) }}
                                    </span>
                                @else
                                    <span class="text-xs opacity-60">{{ __('messages.admin_buffet_created_by_customer') }}</span>
                                @endif
                            </td>
                            <td class="py-3 pe-3">{{ $r->updatedBy?->name ?? '—' }}</td>
                            <td class="py-3">
                                <a href="{{ route('admin.buffet.show', ['buffet' => $r->id]) }}" class="px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                                    {{ __('messages.admin_buffet_view') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
@endsection

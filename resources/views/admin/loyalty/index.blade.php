@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_loyalty_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_loyalty_subtitle') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_staff_back_dashboard') }}
            </a>
        </div>
    </div>

    <div class="mb-4 grid grid-cols-1 lg:grid-cols-3 gap-3">
        <form method="GET" action="{{ route('admin.loyalty.index') }}" class="lg:col-span-2">
            <input
                name="q"
                value="{{ (string) ($q ?? '') }}"
                placeholder="{{ __('messages.admin_loyalty_search_placeholder') }}"
                class="w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
            />
        </form>
    </div>

    <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left opacity-70">
                    <tr>
                        <th class="py-2 pe-3">{{ __('messages.admin_loyalty_phone') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_loyalty_points') }}</th>
                        <th class="py-2">{{ __('messages.admin_loyalty_updated') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $acc)
                        <tr class="border-t {{ $theme === 'dark' ? 'border-white/10' : 'border-slate-900/10' }}">
                            <td class="py-3 pe-3 font-semibold">{{ $acc->phone }}</td>
                            <td class="py-3 pe-3">{{ number_format((int) $acc->points) }}</td>
                            <td class="py-3">{{ $acc->updated_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $accounts->links() }}
        </div>
    </div>
@endsection

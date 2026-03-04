@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_works_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_works_subtitle') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_staff_back_dashboard') }}
            </a>
            <a href="{{ route('admin.works.create') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'bg-amber-300/15 border border-amber-200/25 hover:bg-amber-300/20' : 'bg-amber-200/60 border border-amber-500/20 hover:bg-amber-200/80' }}">
                {{ __('messages.admin_works_add') }}
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-2xl p-4 {{ $theme === 'dark' ? 'bg-emerald-400/10 border border-emerald-300/20 text-emerald-100' : 'bg-emerald-50 border border-emerald-200 text-emerald-900' }}">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4 grid grid-cols-1 lg:grid-cols-3 gap-3">
        <form method="GET" action="{{ route('admin.works.index') }}" class="lg:col-span-2">
            <input
                name="q"
                value="{{ (string) ($q ?? '') }}"
                placeholder="{{ __('messages.admin_works_search_placeholder') }}"
                class="w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
            />
        </form>
    </div>

    <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left opacity-70">
                    <tr>
                        <th class="py-2 pe-3">{{ __('messages.admin_works_col_title') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_works_col_status') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_works_col_date') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_created_by') }}</th>
                        <th class="py-2 pe-3">{{ __('messages.admin_updated_by') }}</th>
                        <th class="py-2">{{ __('messages.admin_works_col_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($works as $work)
                        <tr class="border-t {{ $theme === 'dark' ? 'border-white/10' : 'border-slate-900/10' }}">
                            <td class="py-3 pe-3 font-semibold">{{ $work->display_title }}</td>
                            <td class="py-3 pe-3">
                                {{ $work->is_published ? __('messages.admin_works_published') : __('messages.admin_works_draft') }}
                            </td>
                            <td class="py-3 pe-3">{{ $work->published_at?->format('Y-m-d') }}</td>
                            <td class="py-3 pe-3">{{ $work->createdBy?->name ?? '—' }}</td>
                            <td class="py-3 pe-3">{{ $work->updatedBy?->name ?? '—' }}</td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.works.edit', ['work' => $work->id]) }}" class="px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                                        {{ __('messages.admin_works_edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.works.destroy', ['work' => $work->id]) }}" onsubmit="return confirm('{{ __('messages.admin_works_delete_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-red-500/10 border border-red-500/30 hover:bg-red-500/15' : 'bg-red-50 border border-red-200 hover:bg-red-100' }}">
                                            {{ __('messages.admin_works_delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $works->links() }}
        </div>
    </div>
@endsection

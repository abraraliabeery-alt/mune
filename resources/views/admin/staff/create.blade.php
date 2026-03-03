@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_staff_create_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_staff_create_subtitle') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_staff_back_dashboard') }}
            </a>
            <a href="{{ route('admin.staff.index') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_users_title') }}
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-2xl p-4 {{ $theme === 'dark' ? 'bg-emerald-400/10 border border-emerald-300/20 text-emerald-100' : 'bg-emerald-50 border border-emerald-200 text-emerald-900' }}">
            {{ session('status') }}
        </div>
    @endif

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

    <form method="POST" action="{{ route('admin.staff.store') }}" class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_name') }}</label>
                <input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_email') }}</label>
                <input name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_password') }}</label>
                <input type="password" name="password" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_password_confirm') }}</label>
                <input type="password" name="password_confirmation" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" />
            </div>
        </div>

        <div class="mt-4 flex items-center justify-end">
            <button type="submit" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'bg-amber-300/15 border border-amber-200/25 hover:bg-amber-300/20' : 'bg-amber-200/60 border border-amber-500/20 hover:bg-amber-200/80' }}">
                {{ __('messages.admin_staff_create_submit') }}
            </button>
        </div>
    </form>
@endsection

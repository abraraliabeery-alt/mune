@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_works_create_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_works_create_subtitle') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.works.index') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_works_back') }}
            </a>
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

    <form method="POST" action="{{ route('admin.works.store') }}" enctype="multipart/form-data" class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_title_en') }}</label>
                <input name="title_en" value="{{ old('title_en') }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}" />
            </div>
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_title_ar') }}</label>
                <input name="title_ar" value="{{ old('title_ar') }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}" />
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_body_en') }}</label>
                <textarea name="body_en" rows="5" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}">{{ old('body_en') }}</textarea>
            </div>
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_body_ar') }}</label>
                <textarea name="body_ar" rows="5" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}">{{ old('body_ar') }}</textarea>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_cover') }}</label>
                <input type="file" name="cover_image" accept="image/*" class="mt-1 w-full text-sm" />
            </div>
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_publish') }}</label>
                <div class="mt-2">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_published" value="1" class="rounded" @checked(old('is_published'))>
                        <span>{{ __('messages.admin_works_publish_label') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_images') }}</label>
                <input type="file" name="media_images[]" accept="image/*" multiple class="mt-1 w-full text-sm" />
            </div>
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_videos') }}</label>
                <input type="file" name="media_videos[]" accept="video/*" multiple class="mt-1 w-full text-sm" />
            </div>
        </div>

        <div class="mt-5 flex items-center justify-end gap-2">
            <button type="submit" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'bg-amber-300/15 border border-amber-200/25 hover:bg-amber-300/20' : 'bg-amber-200/60 border border-amber-500/20 hover:bg-amber-200/80' }}">
                {{ __('messages.admin_works_save') }}
            </button>
        </div>
    </form>
@endsection

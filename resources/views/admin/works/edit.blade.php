@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.admin_works_edit_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.admin_works_edit_subtitle') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.works.index') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_works_back') }}
            </a>
            <a href="{{ route('works.show', ['slug' => $work->slug]) }}" target="_blank" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.admin_works_view_public') }}
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

    <form method="POST" action="{{ route('admin.works.update', ['work' => $work->id]) }}" enctype="multipart/form-data" class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_title_en') }}</label>
                <input name="title_en" value="{{ old('title_en', $work->title_en) }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}" />
            </div>
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_title_ar') }}</label>
                <input name="title_ar" value="{{ old('title_ar', $work->title_ar) }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}" />
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_body_en') }}</label>
                <textarea name="body_en" rows="5" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}">{{ old('body_en', $work->body_en) }}</textarea>
            </div>
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_body_ar') }}</label>
                <textarea name="body_ar" rows="5" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}">{{ old('body_ar', $work->body_ar) }}</textarea>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_cover') }}</label>
                @if ($work->cover_image_path)
                    <div class="mt-2 rounded-2xl overflow-hidden {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">
                        <img src="{{ asset('storage/' . $work->cover_image_path) }}" alt="" class="w-full max-h-52 object-cover" />
                    </div>
                @endif
                <input type="file" name="cover_image" accept="image/*" class="mt-2 w-full text-sm" />
            </div>
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_publish') }}</label>
                <div class="mt-2">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_published" value="1" class="rounded" @checked(old('is_published', $work->is_published))>
                        <span>{{ __('messages.admin_works_publish_label') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_images_add') }}</label>
                <input type="file" name="media_images[]" accept="image/*" multiple class="mt-1 w-full text-sm" />
            </div>
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.admin_works_videos_add') }}</label>
                <input type="file" name="media_videos[]" accept="video/*" multiple class="mt-1 w-full text-sm" />
            </div>
        </div>

        <div class="mt-5 flex items-center justify-end gap-2">
            <button type="submit" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'bg-amber-300/15 border border-amber-200/25 hover:bg-amber-300/20' : 'bg-amber-200/60 border border-amber-500/20 hover:bg-amber-200/80' }}">
                {{ __('messages.admin_works_save') }}
            </button>
        </div>
    </form>

    <div class="mt-4 rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        <div class="text-sm font-semibold mb-2">{{ __('messages.admin_works_media_title') }}</div>

        @if (!$work->media->count())
            <div class="text-sm opacity-70">{{ __('messages.admin_works_media_empty') }}</div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach ($work->media as $m)
                    <div class="rounded-2xl overflow-hidden {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">
                        @if ($m->type === 'image')
                            <img src="{{ asset('storage/' . $m->path) }}" alt="" class="w-full h-40 object-cover" />
                        @else
                            <video controls class="w-full h-40 object-cover">
                                <source src="{{ asset('storage/' . $m->path) }}" />
                            </video>
                        @endif

                        <div class="p-2 flex items-center justify-between">
                            <div class="text-xs opacity-70">{{ $m->type }}</div>
                            <form method="POST" action="{{ route('admin.works.media.destroy', ['media' => $m->id]) }}" onsubmit="return confirm('{{ __('messages.admin_works_media_delete_confirm') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs px-3 py-2 rounded-xl {{ $theme === 'dark' ? 'bg-red-500/10 border border-red-500/30 hover:bg-red-500/15' : 'bg-red-50 border border-red-200 hover:bg-red-100' }}">
                                    {{ __('messages.admin_works_media_delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

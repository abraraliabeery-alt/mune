@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-start justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-semibold">{{ $work->display_title }}</h1>
            @if ($work->published_at)
                <p class="text-sm opacity-75">{{ $work->published_at->format('Y-m-d') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('works.index') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                {{ __('messages.works_back') }}
            </a>
        </div>
    </div>

    @if ($work->cover_image_path)
        <div class="rounded-2xl overflow-hidden mb-4 {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">
            <img src="{{ asset('storage/' . $work->cover_image_path) }}" alt="" class="w-full max-h-[520px] object-cover" />
        </div>
    @endif

    @if (trim($work->display_body) !== '')
        <div class="rounded-2xl p-4 sm:p-5 mb-4 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
            <div class="prose max-w-none {{ app()->getLocale() === 'ar' ? 'prose-p:text-right' : '' }}">
                {!! nl2br(e($work->display_body)) !!}
            </div>
        </div>
    @endif

    @php($images = $work->media->where('type', 'image'))
    @php($videos = $work->media->where('type', 'video'))

    @if ($images->count())
        <div class="mb-4">
            <div class="text-sm font-semibold mb-2">{{ __('messages.works_gallery') }}</div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach ($images as $img)
                    <a href="{{ asset('storage/' . $img->path) }}" target="_blank" class="block rounded-2xl overflow-hidden {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">
                        <img src="{{ asset('storage/' . $img->path) }}" alt="" class="w-full h-40 object-cover" />
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if ($videos->count())
        <div>
            <div class="text-sm font-semibold mb-2">{{ __('messages.works_videos') }}</div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                @foreach ($videos as $vid)
                    <div class="rounded-2xl overflow-hidden {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">
                        <video controls class="w-full">
                            <source src="{{ asset('storage/' . $vid->path) }}" />
                        </video>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.works_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.works_subtitle') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($works as $work)
            <a href="{{ route('works.show', ['slug' => $work->slug]) }}" class="block rounded-2xl overflow-hidden {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 hover:bg-white/10' : 'bg-white/80 border border-slate-900/10 hover:bg-white' }}">
                <div class="aspect-[16/10] overflow-hidden">
                    @if ($work->cover_image_path)
                        <img src="{{ asset('storage/' . $work->cover_image_path) }}" alt="" class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full flex items-center justify-center opacity-50">{{ __('messages.works_no_cover') }}</div>
                    @endif
                </div>
                <div class="p-4">
                    <div class="font-semibold">{{ $work->display_title }}</div>
                    @if ($work->published_at)
                        <div class="mt-1 text-xs opacity-70">{{ $work->published_at->format('Y-m-d') }}</div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $works->links() }}
    </div>
@endsection

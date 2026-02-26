@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ __('messages.products_title') }}</h1>
            <p class="text-sm opacity-75">{{ __('messages.products_subtitle') }}</p>
        </div>
        @if (auth()->check() && in_array((string) auth()->user()->role, ['admin', 'staff'], true))
            <a href="{{ route('products.create') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">{{ __('messages.products_new') }}</a>
        @endif
    </div>

    <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach ($products as $product)
                @php($catKey = \Illuminate\Support\Str::lower($product->category))

                <div class="card rounded-2xl overflow-hidden {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/75 border border-slate-900/10' }}">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center overflow-hidden {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">
                                    @if ($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="" class="w-14 h-14 object-cover" />
                                    @else
                                        <span class="text-xl font-semibold {{ $theme === 'dark' ? 'text-amber-200/80' : 'text-amber-700/70' }}">☕</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-semibold tracking-wide">{{ $product->displayName() }}</div>
                                    <div class="text-xs opacity-70">{{ __('messages.category_' . $catKey) }}</div>
                                </div>
                            </div>
                            <div class="text-sm font-semibold tracking-wider">{{ number_format((float) $product->price, 2) }}</div>
                        </div>

                        @php($desc = $product->displayDescription())
                        @if ($desc)
                            <div class="mt-3 text-xs opacity-75 line-clamp-2">{{ $desc }}</div>
                        @endif

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xs px-2 py-1 rounded-xl {{ $product->is_available ? ($theme === 'dark' ? 'bg-emerald-400/10 border border-emerald-300/20 text-emerald-100' : 'bg-emerald-50 border border-emerald-200 text-emerald-800') : ($theme === 'dark' ? 'bg-rose-400/10 border border-rose-300/20 text-rose-100' : 'bg-rose-50 border border-rose-200 text-rose-800') }}">
                                {{ $product->is_available ? __('messages.menu_available') : __('messages.products_no') }}
                            </span>

                            <div class="flex items-center gap-2 text-xs">
                                <a class="px-3 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}" href="{{ route('products.show', $product) }}">{{ __('messages.products_actions_view') }}</a>
                                @if (auth()->check() && in_array((string) auth()->user()->role, ['admin', 'staff'], true))
                                    <a class="px-3 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}" href="{{ route('products.edit', $product) }}">{{ __('messages.products_actions_edit') }}</a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('{{ __('messages.products_delete_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 rounded-xl {{ $theme === 'dark' ? 'bg-red-500/10 border border-red-500/30 hover:bg-red-500/15' : 'bg-red-50 border border-red-200 hover:bg-red-100' }}">
                                            {{ __('messages.products_actions_delete') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        <div class="{{ $theme === 'dark' ? 'text-white' : 'text-slate-900' }}">
            {{ $products->links() }}
        </div>
    </div>
@endsection

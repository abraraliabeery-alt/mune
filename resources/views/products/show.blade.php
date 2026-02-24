@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
            <div class="text-sm text-stone-600">{{ __('messages.category_' . \Illuminate\Support\Str::lower($product->category)) }}</div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('products.edit', $product) }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">{{ __('messages.products_actions_edit') }}</a>
            <a href="{{ route('products.index') }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">{{ __('messages.products_back') }}</a>
        </div>
    </div>

    <div class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-1">
                <div class="rounded-2xl overflow-hidden {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/75 border border-slate-900/10' }}">
                    <div class="h-56 flex items-center justify-center">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" alt="" class="h-44 object-contain drop-shadow" />
                        @else
                            <div class="w-28 h-28 rounded-2xl flex items-center justify-center {{ $theme === 'dark' ? 'bg-amber-300/10 border border-amber-300/20' : 'bg-amber-200/40 border border-amber-500/20' }}">
                                <span class="text-3xl font-semibold {{ $theme === 'dark' ? 'text-amber-200/80' : 'text-amber-700/70' }}">☕</span>
                            </div>
                        @endif
                    </div>
                    <div class="px-4 pb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs px-2 py-1 rounded-xl {{ $product->is_available ? ($theme === 'dark' ? 'bg-emerald-400/10 border border-emerald-300/20 text-emerald-100' : 'bg-emerald-50 border border-emerald-200 text-emerald-800') : ($theme === 'dark' ? 'bg-rose-400/10 border border-rose-300/20 text-rose-100' : 'bg-rose-50 border border-rose-200 text-rose-800') }}">
                                {{ $product->is_available ? __('messages.menu_available') : __('messages.products_no') }}
                            </span>
                            <span class="text-sm font-semibold tracking-wider">{{ number_format((float) $product->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="rounded-2xl p-4 {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/75 border border-slate-900/10' }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div><span class="opacity-70">{{ __('messages.show_price') }}</span> <span class="font-semibold">{{ number_format((float) $product->price, 2) }}</span></div>
                        <div><span class="opacity-70">{{ __('messages.show_available') }}</span> <span class="font-semibold">{{ $product->is_available ? __('messages.products_yes') : __('messages.products_no') }}</span></div>
                    </div>

                    @if ($product->description)
                        <div class="mt-4 text-sm"><span class="opacity-70">{{ __('messages.show_description') }}</span></div>
                        <div class="mt-1 text-sm opacity-90">{{ $product->description }}</div>
                    @endif

                    @if ($product->image_url)
                        <div class="mt-4 text-sm">
                            <span class="opacity-70">{{ __('messages.show_image_url') }}</span>
                            <a class="underline" href="{{ $product->image_url }}" target="_blank">{{ __('messages.show_open') }}</a>
                        </div>
                    @endif

                    <div class="mt-6">
                        <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('{{ __('messages.products_delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'bg-red-500/10 border border-red-500/30 hover:bg-red-500/15' : 'bg-red-50 border border-red-200 hover:bg-red-100' }}">
                                {{ __('messages.products_actions_delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

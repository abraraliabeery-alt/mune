@extends('layouts.app')

@section('content')
    @php($theme = request()->cookie('theme', 'light'))

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">{{ __('messages.products_actions_edit') }}</h1>
        <a href="{{ route('products.show', $product) }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">{{ __('messages.products_back') }}</a>
    </div>

    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="rounded-2xl p-4 sm:p-5 {{ $theme === 'dark' ? 'glass' : 'glass-light' }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_name_en') }}</label>
                <input name="name_en" value="{{ old('name_en', $product->name_en ?? $product->name) }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_name_ar') }}</label>
                <input name="name_ar" value="{{ old('name_ar', $product->name_ar) }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_category') }}</label>
                <select name="category" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}">
                    <option value="">{{ __('messages.form_select') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(old('category', $product->category) === $category)>{{ __('messages.category_' . \Illuminate\Support\Str::lower($category)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_price') }}</label>
                <input name="price" value="{{ old('price', $product->price) }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" />
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_image_file') }}</label>
                <input type="file" name="image_file" accept="image/*" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20 file:bg-white/10 file:border-0 file:rounded-lg file:px-3 file:py-1 file:text-xs file:text-white' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20 file:bg-white file:border-0 file:rounded-lg file:px-3 file:py-1 file:text-xs file:text-slate-900' }}" />
                @if ($product->image_url)
                    <div class="mt-2 text-xs opacity-70">
                        {{ __('messages.form_current_image') }}
                        <a class="underline" href="{{ $product->image_url }}" target="_blank">{{ __('messages.show_open') }}</a>
                    </div>
                @endif
                <div class="mt-1 text-xs opacity-70">{{ __('messages.form_image_or') }}</div>
            </div>

            <div>
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_image_url') }}</label>
                <input name="image_url" value="{{ old('image_url', $product->image_url) }}" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" />
            </div>

            <div class="lg:col-span-2">
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_description_en') }}</label>
                <textarea name="description_en" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" rows="3">{{ old('description_en', $product->description_en ?? $product->description) }}</textarea>
            </div>

            <div class="lg:col-span-2">
                <label class="text-xs tracking-widest opacity-70">{{ __('messages.form_description_ar') }}</label>
                <textarea name="description_ar" class="mt-1 w-full rounded-xl px-3 py-2 outline-none {{ $theme === 'dark' ? 'bg-white/5 border border-white/10 focus:border-white/20' : 'bg-white/70 border border-slate-900/10 focus:border-slate-900/20' }}" rows="3">{{ old('description_ar', $product->description_ar) }}</textarea>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm">
                <input id="is_available" type="checkbox" name="is_available" value="1" class="rounded border" @checked(old('is_available', $product->is_available)) />
                <span class="opacity-90">{{ __('messages.form_available') }}</span>
            </label>

            <div class="flex items-center gap-2">
                <button type="submit" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'bg-amber-300/15 border border-amber-200/25 hover:bg-amber-300/20' : 'bg-amber-200/60 border border-amber-500/20 hover:bg-amber-200/80' }}">
                    {{ __('messages.form_save') }}
                </button>
                <a href="{{ route('products.show', $product) }}" class="text-sm px-4 py-2 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}">
                    {{ __('messages.form_cancel') }}
                </a>
            </div>
        </div>
    </form>
@endsection

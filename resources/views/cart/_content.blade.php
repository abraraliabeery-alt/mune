@php($theme = request()->cookie('theme', 'light'))
@php($tableNumber = $tableNumber ?? null)

@if (empty($items))
    <div class="rounded-2xl p-6 {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/70 border border-slate-900/10' }}">
        <div class="font-semibold mb-1">{{ __('messages.cart_empty_title') }}</div>
        <div class="text-sm opacity-75">{{ __('messages.cart_empty_subtitle') }}</div>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="space-y-3">
                @foreach ($items as $item)
                    @php($lineTotal = ((float) $item['price']) * ((int) $item['qty']))

                    <div class="rounded-2xl p-4 {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/75 border border-slate-900/10' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center overflow-hidden {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}">
                                    @if (!empty($item['image_url']))
                                        <img src="{{ $item['image_url'] }}" alt="" class="w-14 h-14 object-cover" />
                                    @else
                                        <span class="text-xl font-semibold {{ $theme === 'dark' ? 'text-amber-200/80' : 'text-amber-700/70' }}">☕</span>
                                    @endif
                                </div>

                                <div>
                                    <div class="text-sm font-semibold tracking-wide">{{ $item['name'] }}</div>
                                    <div class="mt-1 text-xs opacity-70">{{ number_format((float) $item['price'], 2) }}</div>
                                </div>
                            </div>

                            <div class="text-sm font-semibold tracking-wider">{{ number_format($lineTotal, 2) }}</div>
                        </div>

                        <div class="mt-3 flex items-center justify-between">
                            <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2" data-cart-ajax>
                                @csrf
                                <input type="hidden" name="product_id" value="{{ (int) $item['product_id'] }}" />

                                <label class="text-xs opacity-70" for="qty_{{ (int) $item['product_id'] }}">{{ __('messages.cart_qty') }}</label>
                                <input
                                    id="qty_{{ (int) $item['product_id'] }}"
                                    name="qty"
                                    type="number"
                                    min="0"
                                    max="99"
                                    value="{{ (int) $item['qty'] }}"
                                    class="w-24 px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
                                />
                                <button
                                    type="submit"
                                    class="w-10 h-10 inline-flex items-center justify-center rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                                    aria-label="{{ __('messages.cart_update') }}"
                                    title="{{ __('messages.cart_update') }}"
                                >
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 12a9 9 0 0 1-15.3 6.4" />
                                        <path d="M3 12a9 9 0 0 1 15.3-6.4" />
                                        <path d="M3 12v-4" />
                                        <path d="M3 8h4" />
                                        <path d="M21 12v4" />
                                        <path d="M21 16h-4" />
                                    </svg>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('cart.remove') }}" data-cart-ajax>
                                @csrf
                                <input type="hidden" name="product_id" value="{{ (int) $item['product_id'] }}" />
                                <button
                                    type="submit"
                                    class="w-10 h-10 inline-flex items-center justify-center rounded-xl {{ $theme === 'dark' ? 'bg-red-500/10 border border-red-500/30 hover:bg-red-500/15' : 'bg-red-50 border border-red-200 hover:bg-red-100' }}"
                                    aria-label="{{ __('messages.cart_remove') }}"
                                    title="{{ __('messages.cart_remove') }}"
                                >
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4h8v2" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <aside class="lg:col-span-1">
            <div class="rounded-2xl p-4 {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/75 border border-slate-900/10' }}">
                <div class="flex items-center justify-between">
                    <div class="text-sm opacity-70">{{ __('messages.cart_subtotal') }}</div>
                    <div class="text-lg font-semibold tracking-wider">{{ number_format((float) $subtotal, 2) }}</div>
                </div>
                <div class="mt-2 text-xs opacity-60">{{ __('messages.cart_items_count', ['count' => (int) $count]) }}</div>

                <div class="mt-4">
                    <form method="POST" action="{{ route('cart.table') }}" class="flex items-center gap-2" data-cart-ajax>
                        @csrf
                        <label class="text-xs opacity-70" for="table_number">{{ __('messages.cart_table_label') }}</label>
                        <input
                            id="table_number"
                            name="table_number"
                            type="text"
                            inputmode="numeric"
                            value="{{ (string) ($tableNumber ?? '') }}"
                            placeholder="{{ __('messages.cart_table_placeholder') }}"
                            class="flex-1 px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
                        />
                        <button
                            type="submit"
                            class="w-10 h-10 inline-flex items-center justify-center rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                            aria-label="{{ __('messages.cart_table_save') }}"
                            title="{{ __('messages.cart_table_save') }}"
                        >
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 21H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h11l5 5v9a2 2 0 0 1-2 2Z" />
                                <path d="M17 21v-8H7v8" />
                                <path d="M7 3v4h8" />
                            </svg>
                        </button>
                    </form>

                    @error('table_number')
                        <div class="mt-2 text-xs text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <label class="inline-flex items-center gap-2 text-xs opacity-80">
                                <input type="radio" name="order_type" value="table" class="rounded" @checked(old('order_type', 'table') === 'table')>
                                <span>{{ __('messages.orders_type_table') }}</span>
                            </label>
                            <label class="inline-flex items-center gap-2 text-xs opacity-80">
                                <input type="radio" name="order_type" value="delivery" class="rounded" @checked(old('order_type') === 'delivery')>
                                <span>{{ __('messages.orders_type_delivery') }}</span>
                            </label>
                        </div>

                        <div id="delivery-fields" class="mt-3 {{ old('order_type') === 'delivery' ? '' : 'hidden' }}">
                            <label class="text-xs opacity-70" for="customer_name">{{ __('messages.orders_delivery_name') }}</label>
                            <input
                                id="customer_name"
                                name="customer_name"
                                type="text"
                                value="{{ old('customer_name') }}"
                                class="mt-2 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
                            />
                            @error('customer_name')
                                <div class="mt-2 text-xs text-red-600">{{ $message }}</div>
                            @enderror

                            <label class="mt-3 text-xs opacity-70" for="phone">{{ __('messages.orders_delivery_phone') }}</label>
                            <input
                                id="phone"
                                name="phone"
                                type="text"
                                inputmode="tel"
                                value="{{ old('phone') }}"
                                class="mt-2 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
                            />
                            @error('phone')
                                <div class="mt-2 text-xs text-red-600">{{ $message }}</div>
                            @enderror

                            <label class="mt-3 text-xs opacity-70" for="location_url">{{ __('messages.orders_delivery_address') }}</label>
                            <textarea
                                id="location_url"
                                name="location_url"
                                rows="2"
                                class="mt-2 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
                                placeholder="{{ __('messages.orders_delivery_address_placeholder') }}"
                            >{{ old('location_url') }}</textarea>
                            @error('location_url')
                                <div class="mt-2 text-xs text-red-600">{{ $message }}</div>
                            @enderror

                            <div class="mt-3 text-xs opacity-60">{{ __('messages.orders_delivery_payment_note') }}</div>
                        </div>

                        <div id="loyalty-fields" class="mt-3 {{ old('order_type', 'table') === 'table' ? '' : 'hidden' }}">
                            <label class="text-xs opacity-70" for="loyalty_phone">{{ __('messages.loyalty_phone_optional') }}</label>
                            <input
                                id="loyalty_phone"
                                name="loyalty_phone"
                                type="text"
                                inputmode="tel"
                                value="{{ old('loyalty_phone') }}"
                                class="mt-2 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
                            />
                            <div class="mt-2 text-xs opacity-60">{{ __('messages.loyalty_phone_note') }}</div>
                        </div>

                        <label class="text-xs opacity-70" for="order_notes">{{ __('messages.orders_notes') }}</label>
                        <textarea
                            id="order_notes"
                            name="notes"
                            rows="2"
                            class="mt-2 w-full px-3 py-2 rounded-xl text-sm {{ $theme === 'dark' ? 'bg-white/5 border border-white/10' : 'bg-white/80 border border-slate-900/10' }}"
                            placeholder="{{ __('messages.orders_notes_placeholder') }}"
                        >{{ old('notes') }}</textarea>

                        <button
                            type="submit"
                            class="mt-3 w-full px-4 py-3 rounded-xl {{ $theme === 'dark' ? 'chip hover:bg-white/10' : 'chip-light hover:bg-white/70' }}"
                        >
                            <span class="inline-flex items-center justify-center gap-2 text-sm">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M8 7h12" />
                                    <path d="M8 12h12" />
                                    <path d="M8 17h12" />
                                    <path d="M4 7h.01" />
                                    <path d="M4 12h.01" />
                                    <path d="M4 17h.01" />
                                </svg>
                                <span>{{ __('messages.orders_confirm') }}</span>
                            </span>
                        </button>
                    </form>

                    <script>
                        (function () {
                            const form = document.currentScript && document.currentScript.closest('form');
                            const root = document.currentScript && document.currentScript.closest('aside');
                            if (!root) return;
                            const delivery = root.querySelector('#delivery-fields');
                            const radios = root.querySelectorAll('input[name="order_type"]');
                            if (!delivery || !radios.length) return;
                            function sync() {
                                const selected = root.querySelector('input[name="order_type"]:checked');
                                const isDelivery = selected && selected.value === 'delivery';
                                delivery.classList.toggle('hidden', !isDelivery);
                                const loyalty = root.querySelector('#loyalty-fields');
                                if (loyalty) {
                                    loyalty.classList.toggle('hidden', isDelivery);
                                }
                            }
                            radios.forEach(r => r.addEventListener('change', sync));
                            sync();
                        })();
                    </script>
                </div>
            </div>
        </aside>
    </div>
@endif

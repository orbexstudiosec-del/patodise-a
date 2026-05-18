@extends('layouts.app')

@section('title', 'Checkout · Pato Diseña')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <h1 class="font-display text-2xl sm:text-3xl md:text-4xl">Finalizar compra</h1>

        <form method="POST" action="{{ route('checkout.store') }}" class="mt-6 sm:mt-8 grid lg:grid-cols-3 gap-6 sm:gap-8">
            @csrf
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-ink-200 rounded-2xl p-6">
                    <h2 class="font-semibold mb-4">Tus datos</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <label class="block text-sm">
                            <span class="text-ink-600">Nombre completo *</span>
                            <input name="customer_name" value="{{ old('customer_name', auth()->user()?->name) }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                            @error('customer_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm">
                            <span class="text-ink-600">Email *</span>
                            <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()?->email) }}" required class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                            @error('customer_email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </label>
                        <label class="block text-sm sm:col-span-1">
                            <span class="text-ink-600">Teléfono</span>
                            <input name="customer_phone" value="{{ old('customer_phone') }}" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">
                        </label>
                        <label class="block text-sm sm:col-span-2">
                            <span class="text-ink-600">Dirección de envío (solo para impresiones)</span>
                            <textarea name="shipping_address" rows="2" class="mt-1 w-full border border-ink-200 rounded-lg px-3 py-2">{{ old('shipping_address') }}</textarea>
                        </label>
                    </div>
                </div>

                <div class="bg-white border border-ink-200 rounded-2xl p-6">
                    <h2 class="font-semibold mb-4">Método de pago</h2>
                    <div class="grid sm:grid-cols-3 gap-3">
                        @foreach (['transferencia' => 'Transferencia', 'paypal' => 'PayPal', 'efectivo' => 'Efectivo contra entrega'] as $val => $label)
                            <label class="border border-ink-200 rounded-xl p-3 text-sm cursor-pointer has-[:checked]:border-ink-900 has-[:checked]:bg-ink-100">
                                <input type="radio" name="payment_method" value="{{ $val }}" {{ old('payment_method', 'transferencia') === $val ? 'checked' : '' }} class="accent-black mr-2">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method')<p class="text-xs text-red-600 mt-2">{{ $message }}</p>@enderror
                </div>

                <div class="bg-white border border-ink-200 rounded-2xl p-6">
                    <h2 class="font-semibold mb-4">Notas adicionales</h2>
                    <textarea name="notes" rows="3" class="w-full border border-ink-200 rounded-lg px-3 py-2 text-sm" placeholder="Indicaciones especiales, dedicatoria, etc.">{{ old('notes') }}</textarea>
                </div>
            </div>

            <aside class="bg-ink-900 text-white rounded-2xl p-6 h-fit lg:sticky lg:top-24">
                <h2 class="font-display text-xl mb-4">Tu pedido</h2>
                <ul class="space-y-3 text-sm">
                    @foreach ($items as $item)
                        <li class="flex items-start gap-3">
                            <img src="{{ $item['thumbnail'] }}" alt="" class="w-14 h-14 object-cover rounded-lg">
                            <div class="flex-1 min-w-0">
                                <p class="truncate">
                                    @if ($item['item_type'] === 'gallery')<span class="text-[10px] bg-white/20 text-white px-1.5 py-0.5 rounded mr-1">PACK</span>@endif
                                    {{ $item['item_title'] }}
                                </p>
                                <p class="text-xs text-ink-300">{{ $item['format_label'] }} × {{ $item['quantity'] }}</p>
                            </div>
                            <span>${{ number_format($item['line_total'], 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="border-t border-ink-700 mt-4 pt-4 flex justify-between font-semibold">
                    <span>Total</span><span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <button class="mt-6 w-full bg-white hover:bg-ink-200 text-ink-900 py-3 rounded-full font-medium">Confirmar pedido</button>
                <p class="mt-3 text-xs text-ink-400 text-center">Al confirmar aceptas los términos y condiciones.</p>
            </aside>
        </form>
    </section>
@endsection

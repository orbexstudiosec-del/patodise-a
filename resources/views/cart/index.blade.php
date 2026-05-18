@extends('layouts.app')

@section('title', 'Carrito · Pato Diseña')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        <h1 class="font-display text-2xl sm:text-3xl md:text-4xl">Tu carrito</h1>

        @if (empty($items))
            <div class="mt-10 text-center bg-white rounded-2xl border border-ink-200 p-8 sm:p-16">
                <p class="text-ink-500">Aún no has añadido fotografías ni packs.</p>
                <a href="{{ route('galleries.index') }}" class="mt-4 inline-block bg-ink-900 text-white px-5 py-2.5 rounded-full text-sm hover:bg-ink-700">Explorar galerías</a>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-8 mt-8">
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($items as $item)
                        <div class="flex gap-4 bg-white border border-ink-200 rounded-2xl p-4">
                            <a href="{{ $item['detail_route'] }}" class="shrink-0">
                                <img src="{{ $item['thumbnail'] }}" alt="" class="w-24 h-24 object-cover rounded-xl">
                            </a>
                            <div class="flex-1 min-w-0">
                                @if ($item['item_type'] === 'gallery')
                                    <span class="inline-block text-xs bg-ink-100 text-ink-900 px-2 py-0.5 rounded-full mb-1">Pack completo</span>
                                @endif
                                <a href="{{ $item['detail_route'] }}" class="font-medium hover:text-ink-900 block">{{ $item['item_title'] }}</a>
                                <p class="text-xs text-ink-500 mt-0.5">{{ $item['format_label'] }}</p>
                                @if ($item['subtitle'])
                                    <p class="text-xs text-ink-500">{{ $item['subtitle'] }}</p>
                                @endif
                                <p class="text-sm text-ink-700 mt-1">${{ number_format($item['unit_price'], 2) }} c/u</p>

                                <div class="mt-3 flex items-center gap-3">
                                    @if ($item['item_type'] === 'photo')
                                        <form method="POST" action="{{ route('cart.update', $item['key']) }}" class="flex items-center gap-2">
                                            @csrf @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="20" class="w-16 border border-ink-200 rounded-lg px-2 py-1 text-sm">
                                            <button class="text-xs text-ink-600 hover:text-ink-900">Actualizar</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('cart.remove', $item['key']) }}">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-600 hover:underline">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">${{ number_format($item['line_total'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach

                    <form method="POST" action="{{ route('cart.clear') }}" class="text-right">
                        @csrf @method('DELETE')
                        <button class="text-xs text-ink-500 hover:text-red-600">Vaciar carrito</button>
                    </form>
                </div>

                <aside class="bg-ink-900 text-white rounded-2xl p-6 h-fit lg:sticky lg:top-24">
                    <h2 class="font-display text-xl mb-4">Resumen</h2>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-ink-300">Subtotal</dt><dd>${{ number_format($subtotal, 2) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink-300">Envío</dt><dd>A calcular</dd></div>
                    </dl>
                    <div class="border-t border-ink-700 mt-4 pt-4 flex justify-between text-base font-semibold">
                        <span>Total</span><span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <a href="{{ route('checkout.show') }}" class="mt-6 block text-center bg-white hover:bg-ink-200 text-ink-900 py-3 rounded-full font-medium">Continuar al checkout</a>
                    <a href="{{ route('galleries.index') }}" class="mt-2 block text-center text-sm text-ink-300 hover:text-white">Seguir comprando</a>
                </aside>
            </div>
        @endif
    </section>
@endsection

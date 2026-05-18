@extends('layouts.app')

@section('title', 'Pedido confirmado · Pato Diseña')

@section('content')
    <section class="max-w-3xl mx-auto px-6 py-20 text-center">
        <div class="mx-auto w-16 h-16 rounded-full bg-ink-900 text-white flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="w-8 h-8"><path d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="font-display text-3xl md:text-4xl mt-6">¡Gracias por tu compra!</h1>
        <p class="text-ink-600 mt-2">Tu pedido <span class="font-mono font-semibold">{{ $order->order_number }}</span> fue recibido.</p>
        <p class="text-ink-500 text-sm mt-1">Te enviaremos los datos de pago a <span class="font-medium">{{ $order->customer_email }}</span>.</p>

        <div class="mt-10 text-left bg-white border border-ink-200 rounded-2xl p-6">
            <h2 class="font-semibold mb-4">Detalle</h2>
            <ul class="divide-y divide-ink-100 text-sm">
                @foreach ($order->items as $item)
                    <li class="py-3 flex justify-between gap-3">
                        <span>
                            @if ($item->item_type === 'gallery')<span class="text-[10px] bg-ink-100 text-ink-900 px-1.5 py-0.5 rounded mr-1">PACK</span>@endif
                            {{ $item->item_title }}
                            <span class="text-xs text-ink-500 block">{{ $item->format }} × {{ $item->quantity }}</span>
                        </span>
                        <span class="font-medium">${{ number_format($item->line_total, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="border-t border-ink-200 mt-3 pt-3 flex justify-between font-semibold">
                <span>Total</span><span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <a href="{{ route('galleries.index') }}" class="mt-8 inline-block bg-ink-900 text-white px-6 py-3 rounded-full text-sm hover:bg-ink-700">Seguir explorando</a>
    </section>
@endsection

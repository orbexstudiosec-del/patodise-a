@extends('admin.layout')
@section('title', 'Pedido ' . $order->order_number)
@section('heading', 'Pedido ' . $order->order_number)

@section('content')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-ink-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-ink-200 flex justify-between">
                <h2 class="font-semibold">Items</h2>
                <span class="text-sm text-ink-500">{{ $order->items->sum('quantity') }} productos</span>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[520px]">
                <thead class="bg-ink-50 text-left text-ink-500">
                    <tr>
                        <th class="px-5 py-2">Producto</th>
                        <th class="px-5 py-2">Tipo</th>
                        <th class="px-5 py-2">Formato</th>
                        <th class="px-5 py-2">Cant.</th>
                        <th class="px-5 py-2">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="px-5 py-3">{{ $item->item_title }}</td>
                            <td class="px-5 py-3">
                                @if ($item->item_type === 'gallery')
                                    <span class="text-xs bg-ink-100 text-ink-900 px-2 py-0.5 rounded-full">Pack</span>
                                @else
                                    <span class="text-xs text-ink-500">Foto</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-ink-500">{{ $item->format }}</td>
                            <td class="px-5 py-3">{{ $item->quantity }}</td>
                            <td class="px-5 py-3">${{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="border-t border-ink-200 px-5 py-3 flex justify-between font-semibold">
                <span>Total</span><span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white border border-ink-200 rounded-2xl p-5">
                <h3 class="font-semibold mb-2">Cliente</h3>
                <p class="text-sm">{{ $order->customer_name }}</p>
                <p class="text-sm text-ink-500">{{ $order->customer_email }}</p>
                @if ($order->customer_phone)<p class="text-sm text-ink-500">{{ $order->customer_phone }}</p>@endif
                @if ($order->shipping_address)
                    <p class="text-xs text-ink-500 mt-2">Dirección:</p>
                    <p class="text-sm whitespace-pre-line">{{ $order->shipping_address }}</p>
                @endif
            </div>

            <div class="bg-white border border-ink-200 rounded-2xl p-5">
                <h3 class="font-semibold mb-2">Estado</h3>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex gap-2">
                    @csrf @method('PATCH')
                    <select name="status" class="flex-1 border border-ink-200 rounded-lg px-3 py-2 text-sm">
                        @foreach (['pending' => 'Pendiente', 'paid' => 'Pagado', 'shipped' => 'Enviado', 'completed' => 'Completado', 'cancelled' => 'Cancelado'] as $val => $label)
                            <option value="{{ $val }}" @selected($order->status === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="bg-ink-900 text-white px-4 py-2 rounded-lg text-sm">Guardar</button>
                </form>
                <p class="mt-3 text-xs text-ink-500">Pago: {{ $order->payment_method ?? '—' }}</p>
                <p class="text-xs text-ink-500">Creado: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>

            @if ($order->notes)
                <div class="bg-white border border-ink-200 rounded-2xl p-5">
                    <h3 class="font-semibold mb-1">Notas</h3>
                    <p class="text-sm text-ink-600 whitespace-pre-line">{{ $order->notes }}</p>
                </div>
            @endif

            {{-- Zona peligrosa --}}
            <div class="border border-red-200 rounded-2xl p-5 bg-red-50">
                <h3 class="font-semibold text-red-900 mb-1">Eliminar pedido</h3>
                <p class="text-xs text-red-700 mb-3">Borra el pedido y todos sus items. Esta acción no se puede deshacer.</p>
                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                      onsubmit="return confirm('¿Eliminar definitivamente el pedido {{ $order->order_number }}? Esta acción no se puede deshacer.');">
                    @csrf @method('DELETE')
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                        Eliminar pedido
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

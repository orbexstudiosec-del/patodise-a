@extends('admin.layout')
@section('title', 'Pedidos')
@section('heading', 'Pedidos')

@section('content')
    <div class="bg-white border border-ink-200 rounded-2xl overflow-hidden overflow-x-auto">
        <table class="w-full text-sm min-w-[680px]">
            <thead class="bg-ink-50 text-ink-500 text-left">
                <tr>
                    <th class="px-4 py-3">N.º</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @forelse ($orders as $order)
                    <tr class="hover:bg-ink-50">
                        <td class="px-4 py-3 font-mono"><a href="{{ route('admin.orders.show', $order) }}" class="text-ink-900">{{ $order->order_number }}</a></td>
                        <td class="px-4 py-3">
                            <p>{{ $order->customer_name }}</p>
                            <p class="text-xs text-ink-500">{{ $order->customer_email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">${{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-3"><span class="text-xs bg-ink-100 px-2 py-1 rounded-full">{{ $order->status_label }}</span></td>
                        <td class="px-4 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-sm text-ink-900">Ver</a>
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="inline ml-2"
                                  onsubmit="return confirm('¿Eliminar definitivamente el pedido {{ $order->order_number }}? Esta acción no se puede deshacer.');">
                                @csrf @method('DELETE')
                                <button class="text-sm text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-ink-500">No hay pedidos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
@endsection

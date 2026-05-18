<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function show()
    {
        if ($this->cart->itemsCount() === 0) {
            return redirect()->route('cart.index')->with('status', 'Tu carrito está vacío.');
        }

        return view('checkout.show', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function store(Request $request)
    {
        if ($this->cart->itemsCount() === 0) {
            return redirect()->route('cart.index');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:120',
            'customer_email' => 'required|email|max:160',
            'customer_phone' => 'nullable|string|max:40',
            'shipping_address' => 'nullable|string|max:500',
            'payment_method' => 'required|in:transferencia,paypal,efectivo',
            'notes' => 'nullable|string|max:500',
        ]);

        $items = $this->cart->items();
        $subtotal = $this->cart->subtotal();

        $order = DB::transaction(function () use ($data, $items, $subtotal) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'shipping_address' => $data['shipping_address'] ?? null,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_type' => $item['item_type'],
                    'photo_id' => $item['photo_id'] ?? null,
                    'gallery_id' => $item['gallery_id'] ?? null,
                    'item_title' => $item['item_title'],
                    'format' => $item['format'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        $this->cart->clear();

        return redirect()->route('checkout.success', $order)->with('status', '¡Pedido recibido!');
    }

    public function success(Order $order)
    {
        return view('checkout.success', compact('order'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
        ]);
        $order->update($data);
        return redirect()->route('admin.orders.show', $order)->with('status', 'Estado actualizado.');
    }

    public function destroy(Order $order)
    {
        $number = $order->order_number;
        $order->delete(); // order_items se borran en cascada (FK cascadeOnDelete)
        return redirect()->route('admin.orders.index')->with('status', "Pedido {$number} eliminado.");
    }
}

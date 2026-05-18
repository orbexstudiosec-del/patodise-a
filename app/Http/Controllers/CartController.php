<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Photo;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        return view('cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    public function summary()
    {
        return response()->json($this->snapshot());
    }

    public function addPhoto(Request $request, Photo $photo)
    {
        $data = $request->validate([
            'format' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1|max:20',
        ]);

        $format = $data['format'] ?? 'digital';
        $qty = (int) ($data['quantity'] ?? 1);

        $this->cart->addPhoto($photo, $format, $qty);

        if ($request->wantsJson()) {
            $available = $photo->gallery?->active_formats ?? CartService::FORMATS;
            return response()->json($this->snapshot([
                'message' => "\"{$photo->title}\" añadida al carrito.",
                'addedItem' => [
                    'item_type' => 'photo',
                    'title' => $photo->title,
                    'subtitle' => $photo->gallery?->name,
                    'thumbnail' => $photo->thumbnail_url,
                    'format_label' => $available[$format]['label'] ?? '',
                    'quantity' => $qty,
                ],
            ]));
        }

        return redirect()->route('cart.index')->with('status', "\"{$photo->title}\" añadida al carrito.");
    }

    public function addGallery(Request $request, Gallery $gallery)
    {
        abort_if($gallery->full_price <= 0, 422, 'Esta galería no tiene pack disponible.');

        $this->cart->addGallery($gallery);

        if ($request->wantsJson()) {
            return response()->json($this->snapshot([
                'message' => "Pack \"{$gallery->name}\" añadido al carrito.",
                'addedItem' => [
                    'item_type' => 'gallery',
                    'title' => $gallery->name,
                    'subtitle' => 'Pack completo · ' . $gallery->photos()->published()->count() . ' fotografías',
                    'thumbnail' => $gallery->cover_url,
                    'format_label' => 'Acceso a toda la galería',
                    'quantity' => 1,
                ],
            ]));
        }

        return redirect()->route('cart.index')->with('status', "Pack completo \"{$gallery->name}\" añadido al carrito.");
    }

    public function update(Request $request, string $key)
    {
        $quantity = (int) $request->input('quantity', 1);
        $this->cart->update($key, $quantity);

        if ($request->wantsJson()) {
            return response()->json($this->snapshot());
        }
        return redirect()->route('cart.index');
    }

    public function remove(Request $request, string $key)
    {
        $this->cart->remove($key);

        if ($request->wantsJson()) {
            return response()->json($this->snapshot());
        }
        return redirect()->route('cart.index')->with('status', 'Producto eliminado del carrito.');
    }

    public function clear(Request $request)
    {
        $this->cart->clear();
        if ($request->wantsJson()) {
            return response()->json($this->snapshot());
        }
        return redirect()->route('cart.index');
    }

    private function snapshot(array $extra = []): array
    {
        return array_merge([
            'count' => $this->cart->itemsCount(),
            'subtotal' => $this->cart->subtotal(),
            'items' => $this->cart->items(),
        ], $extra);
    }
}

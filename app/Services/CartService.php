<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const KEY = 'cart';

    public const FORMATS = [
        'digital' => ['label' => 'Descarga digital (alta resolución)', 'multiplier' => 1.0],
        'print_a4' => ['label' => 'Impresión A4 fine art', 'multiplier' => 1.4],
        'print_a3' => ['label' => 'Impresión A3 fine art', 'multiplier' => 1.9],
        'canvas' => ['label' => 'Canvas montado 50×70', 'multiplier' => 2.6],
    ];

    public function all(): array
    {
        return Session::get(self::KEY, []);
    }

    public function addPhoto(Photo $photo, string $format = 'digital', int $quantity = 1): void
    {
        // Formatos efectivos: los de la galería si los hay, o defaults
        $available = $photo->gallery?->active_formats ?? self::FORMATS;
        if (! array_key_exists($format, $available)) {
            // primer formato disponible como fallback
            $format = array_key_first($available) ?? 'digital';
        }
        $cfg = $available[$format];
        $quantity = max(1, $quantity);
        $basePrice = $photo->effective_price;

        $cart = $this->all();
        $key = 'photo:' . $photo->id . ':' . $format;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'item_type' => 'photo',
                'photo_id' => $photo->id,
                'gallery_id' => $photo->gallery_id,
                'item_title' => $photo->title,
                'subtitle' => $photo->gallery?->name,
                'detail_route' => route('photos.show', $photo),
                'thumbnail' => $photo->thumbnail_url,
                'format' => $format,
                'format_label' => $cfg['label'],
                'unit_price' => round($basePrice * (float) $cfg['multiplier'], 2),
                'quantity' => $quantity,
            ];
        }

        Session::put(self::KEY, $cart);
    }

    public function addGallery(Gallery $gallery): void
    {
        $cart = $this->all();
        $key = 'gallery:' . $gallery->id;

        $cart[$key] = [
            'item_type' => 'gallery',
            'photo_id' => null,
            'gallery_id' => $gallery->id,
            'item_title' => $gallery->name,
            'subtitle' => 'Pack completo · ' . $gallery->photos()->published()->count() . ' fotografías',
            'detail_route' => route('galleries.show', $gallery),
            'thumbnail' => $gallery->cover_url,
            'format' => 'full_gallery',
            'format_label' => 'Acceso a toda la galería (descarga digital)',
            'unit_price' => (float) $gallery->full_price,
            'quantity' => 1,
        ];

        Session::put(self::KEY, $cart);
    }

    public function update(string $key, int $quantity): void
    {
        $cart = $this->all();
        if (! isset($cart[$key])) {
            return;
        }
        // Las galerías completas siempre van con cantidad 1
        if ($cart[$key]['item_type'] === 'gallery') {
            $quantity = 1;
        }
        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['quantity'] = $quantity;
        }
        Session::put(self::KEY, $cart);
    }

    public function remove(string $key): void
    {
        $cart = $this->all();
        unset($cart[$key]);
        Session::put(self::KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::KEY);
    }

    public function itemsCount(): int
    {
        return array_sum(array_column($this->all(), 'quantity'));
    }

    public function subtotal(): float
    {
        $sum = 0.0;
        foreach ($this->all() as $item) {
            $sum += $item['unit_price'] * $item['quantity'];
        }
        return round($sum, 2);
    }

    public function items(): array
    {
        $items = [];
        foreach ($this->all() as $key => $item) {
            $item['key'] = $key;
            $item['line_total'] = round($item['unit_price'] * $item['quantity'], 2);
            $items[] = $item;
        }
        return $items;
    }
}

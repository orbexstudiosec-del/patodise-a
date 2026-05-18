<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::publiclyVisible()->published()->withCount(['photos' => fn ($q) => $q->where('is_published', true)]);

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%");
            });
        }

        $galleries = $query->orderByDesc('event_date')->orderBy('sort_order')->paginate(12)->withQueryString();

        return view('galleries.index', compact('galleries', 'search'));
    }

    public function show(Gallery $gallery)
    {
        abort_unless($gallery->is_published, 404);
        // Galerías no públicas no deben verse por slug — solo por /g/{token}
        abort_unless($gallery->isPublic(), 404);

        $gallery->loadCount(['photos' => fn ($q) => $q->where('is_published', true)]);
        $photos = $gallery->photos()->published()->latest()->paginate(24)->withQueryString();

        return view('galleries.show', compact('gallery', 'photos'));
    }
}

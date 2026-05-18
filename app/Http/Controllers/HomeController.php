<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Photo;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featuredGalleries = Gallery::publiclyVisible()->published()
            ->withCount(['photos' => fn ($q) => $q->where('is_published', true)])
            ->where('is_featured', true)
            ->orderByDesc('event_date')
            ->take(3)
            ->get();

        $galleries = Gallery::publiclyVisible()->published()
            ->withCount(['photos' => fn ($q) => $q->where('is_published', true)])
            ->orderByDesc('event_date')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $featuredPhotos = Photo::published()->featured()
            ->whereHas('gallery', fn ($q) => $q->where('visibility', Gallery::VISIBILITY_PUBLIC))
            ->with('gallery')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('featuredGalleries', 'galleries', 'featuredPhotos'));
    }
}

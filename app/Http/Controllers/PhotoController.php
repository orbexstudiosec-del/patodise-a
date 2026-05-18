<?php

namespace App\Http\Controllers;

use App\Models\Photo;

class PhotoController extends Controller
{
    public function show(Photo $photo)
    {
        abort_unless($photo->is_published, 404);
        $photo->load('gallery');

        // Si la galería es privada/no listada y la sesión no la desbloqueó → 404
        if ($photo->gallery && ! $photo->gallery->isUnlocked()) {
            abort(404);
        }

        $related = Photo::published()
            ->where('id', '!=', $photo->id)
            ->when($photo->gallery_id, fn ($q) => $q->where('gallery_id', $photo->gallery_id))
            ->latest()
            ->take(4)
            ->get();

        return view('photos.show', compact('photo', 'related'));
    }
}

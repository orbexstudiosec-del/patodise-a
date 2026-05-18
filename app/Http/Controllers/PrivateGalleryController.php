<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PrivateGalleryController extends Controller
{
    /**
     * Mostrar una galería privada/no listada por su share_token.
     * Si requiere contraseña y no está desbloqueada, mostrar form de unlock.
     */
    public function show(string $token, Request $request)
    {
        $gallery = Gallery::where('share_token', $token)
            ->where('is_published', true)
            ->firstOrFail();

        // Si es privada y no está desbloqueada, mostrar form de contraseña
        if ($gallery->isPrivate() && ! $gallery->isUnlocked()) {
            return view('private.unlock', compact('gallery'));
        }

        // Unlisted: desbloquear automáticamente al visitar (el token YA es la "llave")
        if (! $gallery->isUnlocked()) {
            $gallery->unlock();
        }

        $gallery->loadCount(['photos' => fn ($q) => $q->where('is_published', true)]);
        $photos = $gallery->photos()->published()->latest()->paginate(24)->withQueryString();

        return view('private.show', compact('gallery', 'photos'));
    }

    /**
     * Recibir contraseña de desbloqueo.
     */
    public function unlock(string $token, Request $request)
    {
        $gallery = Gallery::where('share_token', $token)
            ->where('is_published', true)
            ->firstOrFail();

        if (! $gallery->isPrivate()) {
            return redirect()->route('private-gallery.show', $token);
        }

        $request->validate(['password' => 'required|string|max:255']);

        if (! $gallery->checkSharePassword($request->input('password'))) {
            throw ValidationException::withMessages([
                'password' => 'Contraseña incorrecta.',
            ]);
        }

        $gallery->unlock();
        return redirect()->route('private-gallery.show', $token);
    }
}

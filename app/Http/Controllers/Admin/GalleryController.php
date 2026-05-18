<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::withCount('photos')->orderBy('sort_order')->orderByDesc('event_date')->get();
        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data = $this->handleCover($request, $data);
        $data['is_published'] = $request->boolean('is_published', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['formats'] = $this->normalizeFormats($request->input('formats'));
        $data = $this->handleVisibility($request, $data);

        $gallery = Gallery::create($data);
        $msg = 'Galería creada.' . ($gallery->share_url ? ' Enlace privado generado, cópialo abajo.' : '');
        return redirect()->route('admin.galleries.edit', $gallery)->with('status', $msg);
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $this->validateData($request);
        $data = $this->handleCover($request, $data, $gallery);
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['formats'] = $this->normalizeFormats($request->input('formats'));
        $data = $this->handleVisibility($request, $data, $gallery);

        $gallery->update($data);
        return redirect()->route('admin.galleries.edit', $gallery)->with('status', 'Galería actualizada.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->cover_image && ! str_starts_with($gallery->cover_image, 'http')) {
            Storage::disk('public')->delete($gallery->cover_image);
        }
        $gallery->delete();
        return redirect()->route('admin.galleries.index')->with('status', 'Galería eliminada.');
    }

    /** Regenera el share_token (invalida el link anterior). */
    public function regenerateToken(Gallery $gallery)
    {
        abort_if($gallery->isPublic(), 422, 'Esta galería es pública, no usa enlace privado.');
        $gallery->update(['share_token' => Gallery::generateUniqueToken()]);
        return redirect()->route('admin.galleries.edit', $gallery)->with('status', 'Enlace regenerado.');
    }

    private function validateData(Request $request): array
    {
        $validVisibilities = array_keys(Gallery::VISIBILITY_LABELS);
        return $request->validate([
            'name' => 'required|string|max:160',
            'description' => 'nullable|string|max:1500',
            'event_date' => 'nullable|date',
            'location' => 'nullable|string|max:160',
            'full_price' => 'required|numeric|min:0',
            'per_photo_price' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'cover' => 'nullable|image|max:10240',
            'formats' => 'nullable|array',
            'formats.*.enabled' => 'nullable',
            'formats.*.label' => 'nullable|string|max:120',
            'formats.*.multiplier' => 'nullable|numeric|min:0|max:99',
            'visibility' => 'nullable|in:' . implode(',', $validVisibilities),
            'client_name' => 'nullable|string|max:120',
            'share_password' => 'nullable|string|min:4|max:120',
        ]);
    }

    private function normalizeFormats(mixed $input): ?array
    {
        if (! is_array($input)) {
            return null;
        }
        $valid = array_keys(\App\Services\CartService::FORMATS);
        $out = [];
        foreach ($input as $key => $cfg) {
            if (! in_array($key, $valid, true) || ! is_array($cfg)) {
                continue;
            }
            $out[$key] = [
                'enabled' => (bool) ($cfg['enabled'] ?? false),
                'label' => trim((string) ($cfg['label'] ?? '')) ?: \App\Services\CartService::FORMATS[$key]['label'],
                'multiplier' => max(0, (float) ($cfg['multiplier'] ?? \App\Services\CartService::FORMATS[$key]['multiplier'])),
            ];
        }
        return $out ?: null;
    }

    /**
     * - Si visibility es private y vino password no vacío → hashear.
     * - Si vino vacío y la galería ya tenía password → conservar (no sobrescribir con null).
     * - Si visibility cambia a public/unlisted → el booted() del model limpia el password.
     */
    private function handleVisibility(Request $request, array $data, ?Gallery $gallery = null): array
    {
        $newPassword = trim((string) ($data['share_password'] ?? ''));
        if ($newPassword !== '') {
            $data['share_password'] = Hash::make($newPassword);
        } else {
            // No sobrescribir el password existente
            unset($data['share_password']);
        }
        return $data;
    }

    private function handleCover(Request $request, array $data, ?Gallery $gallery = null): array
    {
        if ($request->hasFile('cover')) {
            if ($gallery && $gallery->cover_image && ! str_starts_with($gallery->cover_image, 'http')) {
                Storage::disk('public')->delete($gallery->cover_image);
            }
            $data['cover_image'] = $request->file('cover')->store('galleries', 'public');
        }
        unset($data['cover']);
        return $data;
    }
}

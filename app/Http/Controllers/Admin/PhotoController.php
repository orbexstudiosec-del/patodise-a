<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $query = Photo::with('gallery')->latest();
        if ($galleryId = $request->integer('gallery')) {
            $query->where('gallery_id', $galleryId);
        }
        $photos = $query->paginate(15)->withQueryString();
        $galleries = Gallery::orderBy('name')->get();
        return view('admin.photos.index', compact('photos', 'galleries'));
    }

    public function create(Request $request)
    {
        $galleries = Gallery::orderBy('name')->get();
        $preselected = $request->integer('gallery');
        return view('admin.photos.create', compact('galleries', 'preselected'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data = $this->handleUpload($request, $data);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published', true);

        Photo::create($data);

        return redirect()->route('admin.photos.index')->with('status', 'Fotografía creada.');
    }

    public function edit(Photo $photo)
    {
        $galleries = Gallery::orderBy('name')->get();
        return view('admin.photos.edit', compact('photo', 'galleries'));
    }

    public function update(Request $request, Photo $photo)
    {
        $data = $this->validateData($request, $photo);
        $data = $this->handleUpload($request, $data, $photo);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');

        $photo->update($data);

        return redirect()->route('admin.photos.index')->with('status', 'Fotografía actualizada.');
    }

    public function destroy(Photo $photo)
    {
        if ($photo->image_path && ! str_starts_with($photo->image_path, 'http')) {
            Storage::disk('public')->delete($photo->image_path);
        }
        $photo->delete();

        return redirect()->route('admin.photos.index')->with('status', 'Fotografía eliminada.');
    }

    private function validateData(Request $request, ?Photo $photo = null): array
    {
        return $request->validate([
            'gallery_id' => 'nullable|exists:galleries,id',
            'title' => 'required|string|max:160',
            'description' => 'nullable|string|max:2000',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:120',
            'captured_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'image' => $photo ? 'nullable|image|max:10240' : 'required|image|max:10240',
        ]);
    }

    private function handleUpload(Request $request, array $data, ?Photo $photo = null): array
    {
        if ($request->hasFile('image')) {
            if ($photo && $photo->image_path && ! str_starts_with($photo->image_path, 'http')) {
                Storage::disk('public')->delete($photo->image_path);
            }
            $data['image_path'] = $request->file('image')->store('photos', 'public');
            $data['thumbnail_path'] = $data['image_path'];
        }
        unset($data['image']);
        return $data;
    }
}

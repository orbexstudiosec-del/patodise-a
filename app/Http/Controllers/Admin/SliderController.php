<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Setting;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function edit()
    {
        // Solo galerías públicas pueden ir en el slider del home
        $galleries = Gallery::publiclyVisible()
            ->withCount(['photos' => fn ($q) => $q->where('is_published', true)])
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->get();

        return view('admin.slider.index', compact('galleries'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'slider_enabled' => 'nullable',
            'slider_interval' => 'nullable|integer|min:2|max:30',
            'galleries' => 'nullable|array',
            'galleries.*.featured' => 'nullable',
            'galleries.*.sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        Setting::set('slider_enabled', $request->boolean('slider_enabled') ? '1' : '0');
        Setting::set('slider_interval', (string) ($data['slider_interval'] ?? 6));

        // Update each gallery's featured + sort_order
        foreach ($data['galleries'] ?? [] as $id => $row) {
            $gallery = Gallery::find((int) $id);
            if (! $gallery || ! $gallery->isPublic()) continue;
            $gallery->update([
                'is_featured' => (bool) ($row['featured'] ?? false),
                'sort_order' => (int) ($row['sort_order'] ?? 0),
            ]);
        }

        return redirect()->route('admin.slider.edit')->with('status', 'Slider actualizado.');
    }
}

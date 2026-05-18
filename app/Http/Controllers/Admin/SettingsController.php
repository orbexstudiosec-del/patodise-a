<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /** Llaves admitidas y sus reglas de validación */
    private const SCHEMA = [
        // SEO
        'seo_title' => 'nullable|string|max:80',
        'seo_description' => 'nullable|string|max:200',
        'seo_keywords' => 'nullable|string|max:300',
        // og_image se procesa aparte como upload
        // Identidad
        'site_name' => 'nullable|string|max:80',
        'site_tagline' => 'nullable|string|max:160',
        'business_owner' => 'nullable|string|max:80',
        'business_city' => 'nullable|string|max:80',
        // Contacto
        'contact_phone' => 'nullable|string|max:40',
        'contact_email' => 'nullable|email|max:160',
        // Redes (URLs completas)
        'social_facebook' => 'nullable|url|max:300',
        'social_instagram' => 'nullable|url|max:300',
        'social_whatsapp' => 'nullable|url|max:300',
    ];

    public function edit()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $data = $request->validate(array_merge(self::SCHEMA, [
            'og_image' => 'nullable|image|max:5120',
            'remove_og_image' => 'nullable',
        ]));

        foreach (array_keys(self::SCHEMA) as $key) {
            Setting::set($key, $data[$key] ?? null);
        }

        // OG image upload / remove
        if ($request->boolean('remove_og_image')) {
            $current = Setting::get('seo_og_image');
            if ($current && ! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            Setting::set('seo_og_image', null);
        } elseif ($request->hasFile('og_image')) {
            $current = Setting::get('seo_og_image');
            if ($current && ! str_starts_with($current, 'http')) {
                Storage::disk('public')->delete($current);
            }
            $path = $request->file('og_image')->store('seo', 'public');
            Setting::set('seo_og_image', $path);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Configuración guardada.');
    }
}

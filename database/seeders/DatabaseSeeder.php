<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Cuentas
        User::firstOrCreate(
            ['email' => 'admin@patodisena.com'],
            ['name' => 'Pato Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );
        User::firstOrCreate(
            ['email' => 'cliente@example.com'],
            ['name' => 'Cliente Demo', 'password' => Hash::make('password'), 'role' => 'customer']
        );

        // Galerías de eventos
        $galleries = [
            [
                'name' => 'Pregón de Fiestas 2025',
                'description' => 'Cobertura completa del pregón anual: parada, música, autoridades y rostros del pueblo. 24 fotografías editadas.',
                'event_date' => '2025-09-12',
                'location' => 'Plaza central',
                'full_price' => 80.00,
                'per_photo_price' => 8.00,
                'is_featured' => true,
                'sort_order' => 0,
                'photo_count' => 14,
                'seed_base' => 200,
                'photo_titles' => [
                    'Apertura del pregón', 'Banda de música', 'Discurso del alcalde',
                    'Reina de fiestas', 'Bailarines de apertura', 'Cohetería',
                    'Saludo a la multitud', 'Niños del desfile', 'Carros alegóricos',
                    'Atardecer en la plaza', 'Pirotecnia', 'Sonrisas del público',
                    'Cierre del pregón', 'Detalle del traje típico',
                ],
            ],
            [
                'name' => 'Desfile cívico de noviembre',
                'description' => 'Marcha cívica con instituciones educativas, autoridades y comunidad.',
                'event_date' => '2025-11-08',
                'location' => 'Av. principal',
                'full_price' => 60.00,
                'per_photo_price' => 7.00,
                'is_featured' => true,
                'sort_order' => 1,
                'photo_count' => 10,
                'seed_base' => 300,
                'photo_titles' => [
                    'Apertura cívica', 'Banda colegial', 'Bandera nacional',
                    'Autoridades en el estrado', 'Estudiantes en formación',
                    'Detalle del uniforme', 'Marcha de la unidad', 'Aplauso final',
                    'Bastoneras', 'Cierre del desfile',
                ],
            ],
            [
                'name' => 'Matrimonio Andrea & Mateo',
                'description' => 'Sesión completa de boda — ceremonia, recepción y retratos.',
                'event_date' => '2025-08-23',
                'location' => 'Hacienda Los Sauces',
                'full_price' => 220.00,
                'per_photo_price' => 12.00,
                'is_featured' => false,
                'sort_order' => 2,
                'photo_count' => 8,
                'seed_base' => 400,
                'photo_titles' => [
                    'Llegada de la novia', 'Intercambio de votos', 'Primer beso',
                    'Brindis en la recepción', 'Primer baile', 'Retrato de la pareja',
                    'Detalle del ramo', 'Despedida con cohetes',
                ],
            ],
            [
                'name' => 'Quince años de Camila',
                'description' => 'Sesión y fiesta de quince años con vals, brindis y baile.',
                'event_date' => '2025-10-04',
                'location' => 'Salón Aurora',
                'full_price' => 150.00,
                'per_photo_price' => 10.00,
                'is_featured' => false,
                'sort_order' => 3,
                'photo_count' => 6,
                'seed_base' => 500,
                'photo_titles' => [
                    'Retrato editorial', 'Entrada al salón', 'Vals tradicional',
                    'Brindis familiar', 'Pastel de quince', 'Cierre de fiesta',
                ],
            ],
        ];

        foreach ($galleries as $g) {
            $gallery = Gallery::firstOrCreate(
                ['slug' => Str::slug($g['name'])],
                [
                    'name' => $g['name'],
                    'description' => $g['description'],
                    'event_date' => $g['event_date'],
                    'location' => $g['location'],
                    'full_price' => $g['full_price'],
                    'per_photo_price' => $g['per_photo_price'],
                    'is_featured' => $g['is_featured'],
                    'is_published' => true,
                    'sort_order' => $g['sort_order'],
                    'cover_image' => "https://picsum.photos/seed/{$g['seed_base']}/1600/1000",
                ]
            );

            for ($i = 0; $i < $g['photo_count']; $i++) {
                $title = $g['photo_titles'][$i] ?? "Foto " . ($i + 1);
                $seed = $g['seed_base'] + $i * 3;
                Photo::firstOrCreate(
                    ['title' => $gallery->name . ' · ' . $title],
                    [
                        'gallery_id' => $gallery->id,
                        'slug' => Str::slug($gallery->name . '-' . $title) . '-' . Str::random(4),
                        'description' => "Fotografía de la galería \"{$gallery->name}\" capturada en {$gallery->location}.",
                        'image_path' => "https://picsum.photos/seed/{$seed}/1600/1100",
                        'thumbnail_path' => "https://picsum.photos/seed/{$seed}/600/450",
                        'price' => null, // hereda per_photo_price de la galería
                        'stock' => 99,
                        'location' => $gallery->location,
                        'captured_year' => (int) date('Y', strtotime($g['event_date'])),
                        'is_featured' => $i < 2,
                        'is_published' => true,
                    ]
                );
            }
        }
    }
}

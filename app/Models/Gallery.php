<?php

namespace App\Models;

use App\Services\CartService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory;

    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_UNLISTED = 'unlisted';
    public const VISIBILITY_PRIVATE = 'private';

    public const VISIBILITY_LABELS = [
        self::VISIBILITY_PUBLIC => 'Pública',
        self::VISIBILITY_UNLISTED => 'No listada (solo con enlace)',
        self::VISIBILITY_PRIVATE => 'Privada (enlace + contraseña)',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'event_date',
        'location',
        'full_price',
        'per_photo_price',
        'formats',
        'sort_order',
        'is_published',
        'is_featured',
        'visibility',
        'share_token',
        'share_password',
        'client_name',
    ];

    protected $hidden = ['share_password'];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'full_price' => 'decimal:2',
            'per_photo_price' => 'decimal:2',
            'formats' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Gallery $gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->name);
            }
            // Generar token automáticamente para galerías no públicas
            if ($gallery->visibility !== self::VISIBILITY_PUBLIC && empty($gallery->share_token)) {
                $gallery->share_token = self::generateUniqueToken();
            }
            // Limpiar token y password si vuelve a pública
            if ($gallery->visibility === self::VISIBILITY_PUBLIC) {
                $gallery->share_token = null;
                $gallery->share_password = null;
            }
            // Limpiar password si es unlisted (no requiere)
            if ($gallery->visibility === self::VISIBILITY_UNLISTED) {
                $gallery->share_password = null;
            }
        });
    }

    public static function generateUniqueToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('share_token', $token)->exists());
        return $token;
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Scope: solo galerías visibles en listados públicos */
    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->where('visibility', self::VISIBILITY_PUBLIC);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
                return $this->cover_image;
            }
            return Storage::disk('public')->url($this->cover_image);
        }

        $firstPhoto = $this->photos()->first();
        if ($firstPhoto) {
            return $firstPhoto->thumbnail_url;
        }

        return 'https://placehold.co/1200x800/14140f/ebb47a?text=' . urlencode($this->name);
    }

    public function getShareUrlAttribute(): ?string
    {
        if (! $this->share_token) {
            return null;
        }
        return route('private-gallery.show', $this->share_token);
    }

    public function isPublic(): bool
    {
        return $this->visibility === self::VISIBILITY_PUBLIC;
    }

    public function isPrivate(): bool
    {
        return $this->visibility === self::VISIBILITY_PRIVATE;
    }

    /** Check si esta sesión ya desbloqueó esta galería */
    public function isUnlocked(): bool
    {
        if ($this->isPublic()) {
            return true;
        }
        $unlocked = Session::get('unlocked_galleries', []);
        return in_array($this->id, $unlocked, true);
    }

    public function unlock(): void
    {
        $unlocked = Session::get('unlocked_galleries', []);
        if (! in_array($this->id, $unlocked, true)) {
            $unlocked[] = $this->id;
            Session::put('unlocked_galleries', $unlocked);
        }
    }

    public function checkSharePassword(string $password): bool
    {
        if (! $this->share_password) {
            return false;
        }
        return Hash::check($password, $this->share_password);
    }

    /**
     * @return array<string, array{label: string, multiplier: float}>
     */
    public function getActiveFormatsAttribute(): array
    {
        $defaults = CartService::FORMATS;
        $overrides = $this->formats ?? [];
        $result = [];

        foreach ($defaults as $key => $default) {
            $cfg = $overrides[$key] ?? [];
            $enabled = (bool) ($cfg['enabled'] ?? true);
            if (! $enabled) {
                continue;
            }
            $result[$key] = [
                'label' => $cfg['label'] ?? $default['label'],
                'multiplier' => (float) ($cfg['multiplier'] ?? $default['multiplier']),
            ];
        }

        return $result;
    }
}

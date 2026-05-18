<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'title',
        'slug',
        'description',
        'image_path',
        'thumbnail_path',
        'price',
        'stock',
        'location',
        'captured_year',
        'is_featured',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Photo $photo) {
            if (empty($photo->slug)) {
                $photo->slug = Str::slug($photo->title) . '-' . Str::random(5);
            }
        });
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->price !== null && (float) $this->price > 0) {
            return (float) $this->price;
        }
        return (float) ($this->gallery?->per_photo_price ?? 0);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->resolveAssetUrl($this->image_path)
            ?? 'https://placehold.co/1200x900/14140f/ebb47a?text=Sin+imagen';
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->resolveAssetUrl($this->thumbnail_path) ?? $this->image_url;
    }

    protected function resolveAssetUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return Storage::disk('public')->url($path);
    }
}

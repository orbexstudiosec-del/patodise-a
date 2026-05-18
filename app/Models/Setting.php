<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    private static ?array $cache = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        self::ensureLoaded();
        $value = self::$cache[$key] ?? null;
        return ($value === null || $value === '') ? $default : $value;
    }

    public static function set(string $key, ?string $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()]
        );
        self::ensureLoaded();
        self::$cache[$key] = $value;
    }

    /** Carga todos los settings a memoria una sola vez por request. */
    private static function ensureLoaded(): void
    {
        if (self::$cache !== null) {
            return;
        }
        try {
            self::$cache = self::query()->pluck('value', 'key')->all();
        } catch (\Throwable $e) {
            // Si la tabla aún no existe (migraciones) → no romper
            self::$cache = [];
        }
    }

    /** Reset cache (útil en tests o tras imports masivos) */
    public static function flush(): void
    {
        self::$cache = null;
    }
}

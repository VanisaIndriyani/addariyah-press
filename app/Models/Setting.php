<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static array $cache = [];

    protected static bool $loaded = false;

    public static function getValue(string $key, mixed $default = null): mixed
    {
        self::loadAll();

        return array_key_exists($key, self::$cache) ? self::$cache[$key] : $default;
    }

    public static function setValue(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        self::$cache[$key] = $value;
        self::$loaded = true;
    }

    protected static function loadAll(): void
    {
        if (self::$loaded) {
            return;
        }

        try {
            self::$cache = static::query()->pluck('value', 'key')->all();
        } catch (\Throwable) {
            self::$cache = [];
        }

        self::$loaded = true;
    }
}

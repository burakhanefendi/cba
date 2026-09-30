<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function defaultLocale(): string
    {
        try {
            $locale = static::get('default_locale', 'tr');
        } catch (\Throwable) {
            return 'tr';
        }

        return in_array($locale, ['tr', 'en'], true) ? $locale : 'tr';
    }

    public static function favicon(): array
    {
        $path = static::get('favicon');
        $url = $path
            ? asset('storage/' . $path)
            : asset('storage/media/favicon-cba.png');
        $ext = strtolower(pathinfo($path ?: 'favicon-cba.png', PATHINFO_EXTENSION));

        $type = match ($ext) {
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'webp' => 'image/webp',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'image/png',
        };

        return ['url' => $url, 'type' => $type];
    }
}

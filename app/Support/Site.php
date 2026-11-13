<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Str;

class Site
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return Setting::getValue($key, $default);
    }

    public static function rupiah(mixed $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    public static function query(): string
    {
        return trim(str_replace(['%', '_'], '', request()->string('q')->toString()));
    }

    public static function logo(bool $onDark = false): string
    {
        $custom = self::customLogo();
        if ($custom) {
            return $custom;
        }

        return asset($onDark ? 'img/logo/logo-light.svg' : 'img/logo/logo.svg');
    }

    public static function hasCustomLogo(): bool
    {
        return self::customLogo() !== null;
    }

    public static function waNumber(): string
    {
        $digits = preg_replace('/\D+/', '', (string) self::get('whatsapp', '')) ?? '';
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return $digits;
    }

    public static function waUrl(string $text): string
    {
        return 'https://wa.me/'.self::waNumber().'?text='.rawurlencode($text);
    }

    public static function mapEmbed(?string $value): ?string
    {
        $value = trim(html_entity_decode((string) $value));
        if ($value === '') {
            return null;
        }

        if (preg_match('/src=["\']([^"\']+)["\']/i', $value, $match)) {
            $value = html_entity_decode($match[1]);
        }

        if (! str_starts_with($value, 'https://') || filter_var($value, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $host = strtolower((string) parse_url($value, PHP_URL_HOST));
        $allowed = ['www.google.com', 'google.com', 'maps.google.com', 'www.google.co.id', 'www.openstreetmap.org'];

        return in_array($host, $allowed, true) ? $value : null;
    }

    public static function instagramUrl(): ?string
    {
        $value = trim((string) self::get('instagram', ''));
        if ($value === '') {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return 'https://instagram.com/'.ltrim($value, '@');
    }

    public static function paragraphs(?string $text): string
    {
        $text = trim((string) $text);
        if ($text === '') {
            return '';
        }

        $blocks = preg_split("/\n\s*\n/", str_replace(["\r\n", "\r"], "\n", $text)) ?: [];
        $html = '';
        foreach ($blocks as $block) {
            $block = trim($block);
            if ($block === '') {
                continue;
            }
            $html .= '<p>'.nl2br(e($block), false).'</p>';
        }

        return $html;
    }

    public static function uniqueSlug(string $modelClass, string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'item';
        $slug = $base;
        $i = 2;

        while ($modelClass::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public static function qrisReady(): bool
    {
        $path = (string) self::get('qris_image', '');

        return $path !== '' && is_file(public_path($path));
    }

    private static function customLogo(): ?string
    {
        $directory = public_path('img/logo');
        if (! is_dir($directory)) {
            return null;
        }

        $best = null;
        $bestTime = 0;
        foreach (scandir($directory) ?: [] as $file) {
            if (! preg_match('/\.(png|jpe?g|webp)$/i', $file)) {
                continue;
            }
            if (str_contains(strtolower($file), 'light')) {
                continue;
            }
            $time = filemtime($directory.DIRECTORY_SEPARATOR.$file) ?: 0;
            if ($time >= $bestTime) {
                $bestTime = $time;
                $best = $file;
            }
        }

        return $best ? asset('img/logo/'.$best) : null;
    }
}

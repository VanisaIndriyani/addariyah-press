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

    public static function plain(?string $text, int $limit = 160): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $text)) ?? '');

        return Str::limit($text, $limit, '');
    }

    public static function shareImage(?string $path = null): ?string
    {
        return self::shareMeta($path)['url'] ?? null;
    }

    /** @return array{url: string, width: int, height: int, type: string}|null */
    public static function shareMeta(?string $path = null): ?array
    {
        $relative = self::shareFile($path) ?? self::shareFile(self::logoRelative());
        if ($relative === null) {
            return null;
        }

        $size = @getimagesize(public_path($relative)) ?: [0, 0];
        $ext = strtolower(pathinfo($relative, PATHINFO_EXTENSION));

        return [
            'url' => asset($relative),
            'width' => (int) ($size[0] ?? 0),
            'height' => (int) ($size[1] ?? 0),
            'type' => match ($ext) {
                'png' => 'image/png',
                'webp' => 'image/webp',
                'gif' => 'image/gif',
                default => 'image/jpeg',
            },
        ];
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

    private static function shareFile(?string $path): ?string
    {
        $path = ltrim(str_replace('\\', '/', (string) $path), '/');
        if ($path === '' || str_contains($path, '..') || ! preg_match('/\.(png|jpe?g|webp|gif)$/i', $path)) {
            return null;
        }

        $source = public_path($path);
        if (! is_file($source)) {
            return null;
        }

        if (! function_exists('imagecreatetruecolor')) {
            return $path;
        }

        $stamp = (string) (filemtime($source) ?: 0).'|'.(string) (filesize($source) ?: 0);
        $dest = 'uploads/share/'.md5($path.'|'.$stamp).'.jpg';
        $absolute = public_path($dest);
        if (is_file($absolute) && filesize($absolute) > 0) {
            return $dest;
        }

        return self::writeShareJpeg($source, $absolute) ? $dest : $path;
    }

    private static function writeShareJpeg(string $source, string $dest): bool
    {
        $info = @getimagesize($source);
        if ($info === false) {
            return false;
        }

        [$width, $height, $type] = $info;
        $image = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($source) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($source),
            default => false,
        };
        if ($image === false) {
            return false;
        }

        $longest = max($width, $height, 1);
        $limit = 1200;
        $scale = min(1, $limit / $longest);
        $targetW = max(1, (int) round($width * $scale));
        $targetH = max(1, (int) round($height * $scale));
        $canvas = imagecreatetruecolor($targetW, $targetH);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $targetW, $targetH, $white);
        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $targetW, $targetH, $width, $height);
        imagedestroy($image);

        $directory = dirname($dest);
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            imagedestroy($canvas);

            return false;
        }

        $quality = 74;
        $written = false;
        do {
            $written = imagejpeg($canvas, $dest, $quality);
            $quality -= 12;
        } while ($written && is_file($dest) && filesize($dest) > 180_000 && $quality >= 38);

        if ($written && is_file($dest) && filesize($dest) > 180_000) {
            $smallW = max(1, (int) round($targetW * 0.7));
            $smallH = max(1, (int) round($targetH * 0.7));
            $smaller = imagecreatetruecolor($smallW, $smallH);
            imagecopyresampled($smaller, $canvas, 0, 0, 0, 0, $smallW, $smallH, $targetW, $targetH);
            $written = imagejpeg($smaller, $dest, 62);
            imagedestroy($smaller);
        }

        imagedestroy($canvas);

        return $written && is_file($dest) && filesize($dest) > 0;
    }

    private static function logoRelative(): ?string
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

        return $best ? 'img/logo/'.$best : null;
    }

    private static function customLogo(): ?string
    {
        $file = self::logoRelative();

        return $file ? asset($file) : null;
    }
}

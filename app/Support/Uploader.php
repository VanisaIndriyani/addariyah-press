<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class Uploader
{
    public static function publicFile(UploadedFile $file, string $folder): string
    {
        return self::put($file, public_path('uploads/'.$folder), 'uploads/'.$folder);
    }

    public static function privateFile(UploadedFile $file, string $folder): string
    {
        return self::put($file, storage_path('app/private/'.$folder), $folder);
    }

    public static function deletePublic(?string $path): void
    {
        $path = str_replace('\\', '/', (string) $path);
        if ($path === '' || ! str_starts_with($path, 'uploads/') || str_contains($path, '..')) {
            return;
        }

        $full = public_path($path);
        if (is_file($full)) {
            unlink($full);
        }
    }

    public static function deletePrivate(?string $path): void
    {
        $path = str_replace('\\', '/', (string) $path);
        if ($path === '' || str_contains($path, '..')) {
            return;
        }

        $full = storage_path('app/private/'.$path);
        if (is_file($full)) {
            unlink($full);
        }
    }

    public static function privatePath(?string $path): ?string
    {
        $path = str_replace('\\', '/', (string) $path);
        if ($path === '' || str_contains($path, '..')) {
            return null;
        }

        $full = storage_path('app/private/'.$path);

        return is_file($full) ? $full : null;
    }

    private static function put(UploadedFile $file, string $directory, string $prefix): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'bin');
        $extension = preg_replace('/[^a-z0-9]/', '', $extension) ?: 'bin';
        $name = (string) Str::uuid().'.'.$extension;

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file->move($directory, $name);

        return $prefix.'/'.$name;
    }
}

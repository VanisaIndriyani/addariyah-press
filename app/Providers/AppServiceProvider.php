<?php

namespace App\Providers;

use App\Support\Cart;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $configured = config('app.public_path');
        if (is_string($configured) && $configured !== '' && is_dir($configured)) {
            $this->app->usePublicPath(rtrim($configured, '/\\'));

            return;
        }

        $default = rtrim($this->app->publicPath(), '/\\');
        $parent = dirname(base_path());
        if ($parent !== $default && $this->webRootLooksMoved($parent, $default)) {
            $this->app->usePublicPath($parent);
        }
    }

    private function webRootLooksMoved(string $parent, string $default): bool
    {
        $parentHasLogo = $this->hasRasterLogo($parent);
        $defaultHasLogo = $this->hasRasterLogo($default);
        if ($parentHasLogo && ! $defaultHasLogo) {
            return true;
        }

        return is_file($parent.'/css/app.css') && ! is_file($default.'/css/app.css');
    }

    private function hasRasterLogo(string $dir): bool
    {
        $logo = $dir.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'logo';
        if (! is_dir($logo)) {
            return false;
        }

        foreach (scandir($logo) ?: [] as $file) {
            if (preg_match('/\.(png|jpe?g|webp)$/i', $file) && ! str_contains(strtolower($file), 'light')) {
                return true;
            }
        }

        return false;
    }

    public function boot(): void
    {
        Paginator::defaultView('partials.pagination');

        Blade::directive('rupiah', function (string $expression) {
            return "<?php echo e(\\App\\Support\\Site::rupiah($expression)); ?>";
        });

        View::composer('layouts.public', function ($view) {
            try {
                $count = Cart::count();
            } catch (\Throwable) {
                $count = 0;
            }

            $view->with('cartCount', $count);
        });
    }
}

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
        //
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

<?php

namespace App\Providers;

use App\Models\ProductCategory;
use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $appUrl = (string) config('app.url');

        if ($appUrl !== '' && ($this->app->runningInConsole() || $this->app->environment('production'))) {
            URL::forceRootUrl($appUrl);

            if (Str::startsWith($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }

        Product::observe(ProductObserver::class);

        View::composer('partials.website.navbar', function ($view): void {
            $navbarCategories = ProductCategory::query()
                ->where('is_active', true)
                ->with([
                    'products' => fn ($query) => $query
                        ->where('is_active', true)
                        ->orderBy('name'),
                ])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            $view->with('navbarCategories', $navbarCategories);
        });

        Permission::created(function (Permission $permission): void {
            $adminRole = Role::query()
                ->where('name', 'admin')
                ->where('guard_name', $permission->guard_name)
                ->first();

            if (! $adminRole) {
                return;
            }

            if (! $adminRole->hasPermissionTo($permission)) {
                $adminRole->givePermissionTo($permission);
            }
        });
    }
}

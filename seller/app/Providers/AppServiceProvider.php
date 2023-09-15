<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      Schema::defaultStringLength(191);
 
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      \Illuminate\Support\Collection::macro('recursive', function () {
    return $this->map(function ($value) {
        if (is_array($value) || is_object($value)) {
            return collect($value)->recursive();
        }

        return $value;
    });
});
    }
}

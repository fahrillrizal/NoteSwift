<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TodoService;
use App\Services\TodoServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TodoServiceInterface::class, TodoService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

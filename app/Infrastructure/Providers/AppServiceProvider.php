<?php

namespace App\Infrastructure\Providers;

use App\Application\UserDataSource\UserDataSource;
use App\DataSource\Database\EloquentUserDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
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
        //
        $this->app->bind(WalletDataSource::class, function ($app) {
            return new WalletDataSource();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->app->bind(UserDataSource::class, function () {
//            return new EloquentUserDataSource();
//        });
    }
}

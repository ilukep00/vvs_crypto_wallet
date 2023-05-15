<?php

namespace App\Infrastructure\Providers;

use App\Application\UserDataSource\UserDataSource;
use App\DataSource\Database\EloquentUserDataSource;
use App\Infrastructure\Persistence\CoinDataSource;
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
        $this->app->bind(CoinDataSource::class, function () {
            return new CoinDataSource();
        });

        $this->app->bind(WalletDataSource::class, function () {
            return new WalletDataSource();
        });
    }
}

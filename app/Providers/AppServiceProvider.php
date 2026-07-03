<?php

namespace App\Providers;

use App\Repositories\Eloquent\CompanyRepository;
use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
          $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
        );

         $this->app->bind(
        CompanyRepositoryInterface::class,
        CompanyRepository::class
    );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enablePasswordGrant();
        
        Passport::tokensExpireIn(CarbonInterval::days(30));
        Passport::refreshTokensExpireIn(CarbonInterval::days(7));
    }
}

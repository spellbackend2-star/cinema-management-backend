<?php

namespace App\Providers;

use App\Repositories\Eloquent\CompanyRepository;
use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\Eloquent\BookingRepository;
use App\Repositories\Eloquent\CinemaRepository;
use App\Repositories\Eloquent\MovieRepository;
use App\Repositories\Eloquent\ScreenRepository;
use App\Repositories\Eloquent\SeatCategoryRepository;
use App\Repositories\Eloquent\SeatRepository;
use App\Repositories\Eloquent\ShowPriceRepository;
use App\Repositories\Eloquent\ShowRepository;
use App\Repositories\Eloquent\ShowSeatRepository;
use App\Repositories\Eloquent\ShowScheduleRepository;
use App\Repositories\Eloquent\StaffRepository;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\CinemaRepositoryInterface;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\Interfaces\ScreenRepositoryInterface;
use App\Repositories\Interfaces\SeatCategoryRepositoryInterface;
use App\Repositories\Interfaces\SeatRepositoryInterface;
use App\Repositories\Interfaces\ShowPriceRepositoryInterface;
use App\Repositories\Interfaces\ShowRepositoryInterface;
use App\Repositories\Interfaces\ShowScheduleRepositoryInterface;
use App\Repositories\Interfaces\ShowSeatRepositoryInterface;
use App\Repositories\Interfaces\StaffRepositoryInterface;
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
        $this->app->bind(
            CinemaRepositoryInterface::class,
            CinemaRepository::class
        );
        $this->app->bind(
            StaffRepositoryInterface::class,
            StaffRepository::class
        );
        $this->app->bind(
            ScreenRepositoryInterface::class,
            ScreenRepository::class
        );
        $this->app->bind(
            SeatCategoryRepositoryInterface::class,
            SeatCategoryRepository::class
        );
        $this->app->bind(
            SeatRepositoryInterface::class,
            SeatRepository::class
        );

        $this->app->bind(
            MovieRepositoryInterface::class,
            MovieRepository::class
        );
        $this->app->bind(
            ShowScheduleRepositoryInterface::class,
            ShowScheduleRepository::class
        );
        $this->app->bind(
            ShowRepositoryInterface::class,
            ShowRepository::class
        );
        $this->app->bind(
            ShowPriceRepositoryInterface::class,
            ShowPriceRepository::class
        );
        $this->app->bind(
            ShowSeatRepositoryInterface::class,
            ShowSeatRepository::class
        );
        $this->app->bind(
            BookingRepositoryInterface::class,
            BookingRepository::class
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

<?php

namespace App\Providers;

use App\Contracts\Interfaces\AuthInterface;
use App\Contracts\Interfaces\CanteenInterface;
use App\Contracts\Interfaces\CanteenOwnerInterface;
use App\Contracts\Interfaces\MenuInterface;
use App\Contracts\Interfaces\OrderInterface;
use App\Contracts\Interfaces\ReviewInterface;
use App\Contracts\Interfaces\TransactionInterface;
use App\Contracts\Repositories\AuthRepository;
use App\Contracts\Repositories\CanteenOwnerRepository;
use App\Contracts\Repositories\CanteenRepository;
use App\Contracts\Repositories\MenuRepository;
use App\Contracts\Repositories\OrderRepository;
use App\Contracts\Repositories\ReviewRepository;
use App\Contracts\Repositories\TransactionRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public array $bindings = [
        AuthInterface::class => AuthRepository::class,
        MenuInterface::class => MenuRepository::class,
        CanteenOwnerInterface::class => CanteenOwnerRepository::class,
        CanteenInterface::class => CanteenRepository::class,
        TransactionInterface::class => TransactionRepository::class,
        OrderInterface::class => OrderRepository::class,
        ReviewInterface::class => ReviewRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->bindings as $key => $value) {
            $this->app->bind($key, $value);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

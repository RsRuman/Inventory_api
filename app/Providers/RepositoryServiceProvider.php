<?php

namespace App\Providers;

use App\Interfaces\AuthenticationRepositoryInterface;
use App\Interfaces\InventoryRepositoryInterface;
use App\Interfaces\ItemRepositoryInterface;
use App\Repositories\AuthenticationRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\ItemRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticationRepositoryInterface::class, AuthenticationRepository::class);
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
    }
}

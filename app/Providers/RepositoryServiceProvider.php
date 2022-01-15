<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\EloquentRepositoryInterface; 
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\BaseRepository; 
use App\Repositories\Eloquent\BaseCacheRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register Interface and Repository in here
        // You must place Interface in first place
        // If you dont, the Repository will not get readed.
        $this->app->bind(EloquentRepositoryInterface::class, BaseCacheRepository::class);
        // $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
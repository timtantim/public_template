<?php

namespace App\Providers;

use App\Models\Pages;
use App\Models\User;
use App\Observers\PageObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Managers\Shop\IShopManager; 
use App\Managers\Shop\ShopManager; 
use App\Managers\Message\IMessageManager; 
use App\Managers\Message\MessageManager; 
use URL;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IShopManager::class, function ($app) {
            return new \App\Managers\Shop\ShopManager($app);
        });
        $this->app->bind(IMessageManager::class, MessageManager::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // URL::forceRootUrl(env('PROXY_URL','')); // 反向代理的URL (若不使用反向代理，請務必註解)
        Schema::defaultStringLength(191);
        Pages::observe(PageObserver::class);
        User::observe(UserObserver::class);    
    }
}

<?php

namespace App\Providers;

use Carbon\Carbon;
use Config;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use URL;
use App\Passport\PassportClientRepository;
use Illuminate\Auth\Notifications\ResetPassword;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindPassportClientRepository();
    }
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!$this->app->routesAreCached()) {
            Passport::routes();

        }
        // Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');
        // Passport::loadKeysFrom(__DIR__.'../../storage');
        // Passport::loadKeysFrom(__DIR__.'../storage');
        // Passport::hashClientSecrets();  // 加上這個東西害我搞了兩天

        // Passport::tokensExpireIn(now()->addDays(1));
        // Passport::refreshTokensExpireIn(now()->addDays(1));
        // Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        // Passport::personalAccessTokensExpireIn(now()->addDays(1));

        Passport::tokensExpireIn(Carbon::now()->addHours(1));
        Passport::refreshTokensExpireIn(Carbon::now()->addHours(1));
        Passport::personalAccessTokensExpireIn(Carbon::now()->addHours(1));
        Passport::tokensCan([
            'read' => '允許讀取',
            'write' => '允許寫入',
            'edit' => '允許修改',
            'remove' => '允許刪除',
        ]);

        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'language' => \App::getLocale(),
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return url(route('password.reset', [
                'language' => \App::getLocale(),
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        });

    }

    /**
     * Register the client repository.
     *
     * @return void
     */
    protected function bindPassportClientRepository()
    {
        $this->app->bind(\Laravel\Passport\Bridge\ClientRepository::class, PassportClientRepository::class);
    }
}

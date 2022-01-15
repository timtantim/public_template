<?php

namespace Tests\Feature\Auth;
use Artisan;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // This will create user token
        // Artisan::call('passport:client --personal --name=custom_token');
        // Artisan::call('passport:client --password --name=password_token');
    }

    public function test_login_screen_can_be_rendered()
    {
        // $this->withoutExceptionHandling();
        $response = $this->get(app()->getLocale().'/login');
        $response->assertStatus(200);
    }

    // public function test_users_can_authenticate_using_the_login_screen()
    // {
    //     $this->withoutExceptionHandling();
    //     $user = User::factory()->create();
    //     $response = $this->post(app()->getLocale().'/login', [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ]);
    //     $this->assertAuthenticated();
    //     $response->assertRedirect(\App::getLocale().RouteServiceProvider::HOME);
    // }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post(app()->getLocale().'/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}

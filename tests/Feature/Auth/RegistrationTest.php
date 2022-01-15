<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use WithoutMiddleware;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get(app()->getLocale() . '/register');

        $response->assertStatus(200);
    }

    // public function test_new_users_can_register()
    // {
    //     $this->withoutMiddleware();
    //     $this->withoutExceptionHandling();
    //     $response = $this->post(app()->getLocale() . '/register', [
    //         'name' => 'Test User',
    //         'email' => 'test@example.com',
    //         'password' => 'password',
    //         'password_confirmation' => 'password',
    //     ]);
    //     $this->assertAuthenticated();
    //     $response->assertRedirect(\App::getLocale() . RouteServiceProvider::HOME);
    // }
}

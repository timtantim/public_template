<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseMigrations;
    
    public function setUp(): void
    {
        parent::setUp();
        // $this->artisan('db:seed');
    }
    public function test_all_user()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/all_user');

        $response->assertStatus(200);
    }


    public function test_all_delete_user()
    {
        // $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/all_delete_user');

        $response->assertStatus(200);
    }

    public function test_query_user()
    {
        // $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/query_user');

        $response->assertStatus(200);
    }

    public function test_query_delete_user()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/query_delete_user',['row_perpage'=>1]);
        $response->assertStatus(200);
    }

    public function test_store_user()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/store_user',['name'=>'Test_Name','email'=>'test@gmail.com','password'=>'88888888']);
        $response->assertStatus(201);
    }

    public function test_show_user()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->get('api/show_user/1');
        $response->assertStatus(404);
    }

    public function test_show_delete_user()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->get('api/show_delete_user/1');
        $response->assertStatus(404);
    }

    public function test_update_or_create_user()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/update_or_create_user',[
            'unique_attribute'=>'{"id":"1"}',
            'update_attribute'=>'{"name":"Test_Name","email":"Test@gmail.com","password":"88888888"}'
        ]);
        $response->assertStatus(201);
    }

    public function test_currency_exchange()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/update_user',[
            'where_attribute'=>'{"id":"1"}',
            'update_attribute'=>'{"name":"Test_Name","email":"Test@gmail.com","password":"88888888"}'
        ]);
        $response->assertStatus(200);
    }

    public function test_destroy_user()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->delete('api/destroy_user/1');
        $response->assertStatus(200);
    }

    public function test_force_destroy_user()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->delete('api/force_destroy_user/1');
        $response->assertStatus(404);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }
    /**
     * A basic feature test to currency_exchange.
     *
     * @return void
     */
    public function test_currency_exchange()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/currency_exchange', [
            'source_currency' => 'TWD',
            'target_currency' => 'JPY',
            'price' => '1234',
        ]);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test to currency_exchange Expect correct price.
     *
     * @return void
     */
    public function test_currency_exchange_expect_correct_price()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/currency_exchange', [
            'source_currency' => 'TWD',
            'target_currency' => 'JPY',
            'price' => '1234',
        ]);
        $response->assertStatus(200);
        $this->assertEquals([
            "message" => "Target price",
            "error" => false,
            "code" => 200,
            "results" => "4,527.55",
        ], $response->original);
    }

    /**
     * A basic feature test to currency_exchange with invalid input target_currency.
     *
     * @return void
     */
    public function test_currency_exchange_with_invalid_input_target_currency()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/currency_exchange', [
            'source_currency' => 'TWD',
            'price' => '1234',
        ]);
        $response->assertSessionHasErrors('target_currency');
    }

    /**
     * A basic feature test to currency_exchange with invalid input source_currency.
     *
     * @return void
     */
    public function test_currency_exchange_with_invalid_input_source_currency()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/currency_exchange', [
            'target_currency' => 'TWD',
            'price' => '1234',
        ]);
        $response->assertSessionHasErrors('source_currency');
    }

    /**
     * A basic feature test to currency_exchange with invalid input price.
     *
     * @return void
     */
    public function test_currency_exchange_with_invalid_input_price()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/currency_exchange', [
            'source_currency' => 'JPY',
            'target_currency' => 'TWD',
        ]);
        $response->assertSessionHasErrors('price');
    }

    /**
     * A basic feature test to currency_exchange expect price to be integer.
     *
     * @return void
     */
    public function test_currency_exchange_expect_price_to_be_integer()
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('api/currency_exchange', [
            'source_currency' => 'JPY',
            'target_currency' => 'TWD',
            'price' =>'I am not Integer'
        ]);
        $response->assertSessionHasErrors('price');
    }
}

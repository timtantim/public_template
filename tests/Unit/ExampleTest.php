<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Managers\Shop\IShopManager;
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_can_use_ebay_service()
    {
        $factory = app(IShopManager::class);
        $service = $factory->make('ebay');
        $products = $service->getProducts();
        // dump($products);
        self::assertEquals([
            'Ebay Product Sample #1',
            'Ebay Product Sample #2',
            'Ebay Product Sample #3',
        ], $products);
       
    }
    public function test_can_use_amazon_service()
    {
        $factory = app(IShopManager::class);
        $service = $factory->make('amazon');
        $products = $service->getProducts();
        // dump($products);
        self::assertEquals([
            'Amazon Product Sample #1',
            'Amazon Product Sample #2',
            'Amazon Product Sample #3',
        ], $products);
    }
}

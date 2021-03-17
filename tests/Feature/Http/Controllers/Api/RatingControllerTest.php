<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class RatingControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Category::factory()->create();
        
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
    }


    public function test_rate_product()
    {
        $product = Product::factory()->create();

        $data = [
            'score' => 5.0,
        ];
        $response = $this->postJson(route('rating.product', $product), $data);

        $response->assertSuccessful()
            ->assertHeader('content-type', 'application/json');
        
        //$this->assertDatabaseHas('products', $data);
    }
}

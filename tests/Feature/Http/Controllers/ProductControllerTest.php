<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;

use App\Models\Product;
use App\Models\User;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
    }

    public function test_index()
    {
        Product::factory(5)->create();

        $response = $this->getJson(route('products.index'));

        $response->assertSuccessful()
            ->assertHeader('content-type', 'application/json')
            ->assertJsonStructure([
                'current_page',
                'data',
            ])
            ->assertJsonCount(5, 'data');
    }

    public function test_create_new_product()
    {
        $data = [
            'name' => $this->faker->sentence(3),
            'price' => $this->faker->randomFloat(2, 20000, 30000),
        ];
        $response = $this->postJson(route('products.store'), $data);

        $response->assertSuccessful()
            ->assertHeader('content-type', 'application/json');
        
        $this->assertDatabaseHas('products', $data);
    }

    public function test_product_validation()
    {
        $data = [
            'name' => '',
            'price' => '',
        ];
        $response = $this->postJson(route('products.store'), $data);

        $response->assertJsonValidationErrors(['name', 'price'])
            ->assertStatus(422);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Update Product',
            'price' => 20000,
        ];

        $response = $this->patchJson(route('products.update', $product), $data)
            ->assertSuccessful()
            ->assertHeader('content-type', 'application/json')
            ->assertJson(['name' => $data['name']]);
    }

    public function test_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson(route('products.show', $product));

        $response->assertSuccessful()
            ->assertHeader('content-type', 'application/json');
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('products.destroy', $product));

        $response->assertNoContent();

        $this->assertDeleted($product);
        //$this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}

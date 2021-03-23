<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Category::factory()->create();
        Sanctum::actingAs(
            $this->user,
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
                'meta' => [
                    'current_page'
                ],
                'data',
            ])
            ->assertJsonCount(5, 'data');
    }

    public function test_create_new_product()
    {
        $this->withoutExceptionHandling();

        $data = [
            'name' => $this->faker->sentence(3),
            'price' => $this->faker->randomFloat(2, 20000, 30000),
            'category_id' => 1
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
            'category_id' => ''
        ];
        $response = $this->postJson(route('products.store'), $data);

        $response->assertJsonValidationErrors(['name', 'price', 'category_id'])
            ->assertStatus(422);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create();

        $data = [
            'name' => 'Update Product',
            'price' => 20000,
            'category_id' => 1
        ];

        $response = $this->patchJson(route('products.update', $product), $data)
            ->assertSuccessful()
            ->assertHeader('content-type', 'application/json')
            ->assertJson(['name' => $data['name'], 'price' => $data['price']]);
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
        $this->withoutExceptionHandling();

        $product = Product::factory()->create([
            'created_by' => $this->user->id
        ]);

        $response = $this->deleteJson(route('products.destroy', $product));

        $response->assertNoContent();

        $this->assertDeleted($product);
        //$this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}

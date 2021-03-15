<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Laravel\Sanctum\Sanctum;

use App\Models\Category;
use App\Models\User;

class CategoryControllerTest extends TestCase
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
        Category::factory(5)->create();

        $response = $this->getJson(route('categories.index'));

        $response->assertSuccessful()
            ->assertHeader('content-type', 'application/json')
            ->assertJsonStructure([
                'data',
            ])
            ->assertJsonCount(5, 'data');
    }

    public function test_create_new_category()
    {
        $data = [
            'name' => $this->faker->sentence(3),
        ];
        $response = $this->postJson(route('categories.store'), $data);

        $response->assertSuccessful()
            ->assertHeader('content-type', 'application/json');
        
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_category_validation()
    {
        $data = [
            'name' => '',
        ];
        $response = $this->postJson(route('categories.store'), $data);

        $response->assertJsonValidationErrors(['name'])
            ->assertStatus(422);
    }

    public function test_update_category()
    {
        $category = Category::factory()->create();

        $data = [
            'name' => 'Updated Categories'
        ];

        $response = $this->patchJson(route('categories.update', $category), $data)
            ->assertSuccessful()
            ->assertHeader('content-type', 'application/json')
            ->assertJson(['name' => $data['name']]);
    }

    public function test_show_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson(route('categories.show', $category));

        $response->assertSuccessful()
            ->assertHeader('content-type', 'application/json');
    }

    public function test_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson(route('categories.destroy', $category));

        $response->assertNoContent();

        $this->assertDeleted($category);
        //$this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}

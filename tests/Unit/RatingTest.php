<?php

namespace Tests\Unit;

#use PHPUnit\Framework\TestCase;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_rateable_category()
    {
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $category->rate($user, 10.0);
        $category->rate($user, 5.0);

        $this->assertEquals(7.5, $category->rating());
    }

    public function test_rateable_product()
    {
        //$this->withoutExceptionHandling();

        $user = User::factory()->create();

        Category::factory()->create();

        $product = Product::factory()->create();

        $product->rate($user, 10.0);
        $product->rate($user, 5.0);

        $this->assertEquals(7.5, $product->rating());
    }
}

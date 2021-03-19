<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \Faker\Factory as Faker;

use App\Traits\CanBeRated;

class Product extends Model
{
    use HasFactory, CanBeRated;

    protected $fillable = [
        'name',
        'price',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function booted()
    {
        static::creating(function(Product $product){
            $faker = Faker::create();

            $product->image_url = $faker->imageUrl();
        });
    }
}

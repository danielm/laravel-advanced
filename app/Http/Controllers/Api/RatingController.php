<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\RatingRequest;

use App\Http\Resources\RatingResource;

class RatingController extends Controller
{
    public function category(RatingRequest $request, Category $category)
    {
        $category->rate($request->user(), $request->get('score'));

        return response()->json($category->ratings()->get());
    }

    public function product(RatingRequest $request, Product $product)
    {
        $product->rate($request->user(), $request->get('score'));

        return response()->json(RatingResource::collection($product->ratings()->with('user')->get()));
    }
}


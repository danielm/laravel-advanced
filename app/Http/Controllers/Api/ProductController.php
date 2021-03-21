<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Product;
use Illuminate\Http\Request;

use App\Http\Requests\ProductRequest;

use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;

use App\Jobs\SendProductCreatedEmail;

class ProductController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ProductCollection(Product::latest()->with(['createdBy', 'category'])->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated() + [
            'created_by' => $request->user()->id
        ];

        $product = Product::create($data);

        $this->dispatch(new SendProductCreatedEmail($request->user()->email));

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        //$this->authorize('update', $product);
        // if ($request->user()->cannot('create', Product::class)) {
        //     abort(403);
        // }

        $product->update($request->validated());

        return response()->json(new ProductResource($product));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}

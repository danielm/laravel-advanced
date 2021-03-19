<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        //dd($this);

        return [
            'id' => $this->id, 
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image_url,
            'category' => [
                'name' => $this->category->name
            ],
            'author' => new UserResource($this->whenLoaded('createdBy'))
        ];
    }
}

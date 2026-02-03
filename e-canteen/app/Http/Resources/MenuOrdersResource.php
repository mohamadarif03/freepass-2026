<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuOrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->menu->id,
            'name' => $this->menu->name,
            'slug' => $this->menu->slug,
            'description' => $this->menu->description,
            'price' => $this->menu->price,
            'image' => isset($this->menu->image) ? asset('storage/' . $this->menu->image) : null,
        ];
    }
}

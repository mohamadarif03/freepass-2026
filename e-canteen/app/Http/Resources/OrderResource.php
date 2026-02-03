<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => UserResource::make($this->user),
            'payment_method' => $this->payment_method,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'menus' => MenuOrdersResource::collection($this->menus),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

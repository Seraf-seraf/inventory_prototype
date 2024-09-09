<?php

namespace App\Http\Resources;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->product_id,
            'price' => $this->whenNotNull($this->price),
            'quantity' => $this->quantity,
            'remainder' => $this->remainder,
            'remainder_in_money' => $this->remainder_in_money,
            'inventory_error' => $this->when(
                $this->inventoryError,
                optional($this->inventoryError)->error
            ),
            'inventory_error_in_money' => $this->when(
                $this->inventoryError,
                optional($this->inventoryError)->error_in_money
            )
        ];
    }
}

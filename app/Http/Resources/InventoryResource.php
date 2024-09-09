<?php

namespace App\Http\Resources;

use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
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
            'quantity' => $this->quantity,
            'remainder' => $this->remainder,
            'price' => $this->inventoryError->error
                       ? $this->inventoryError->error_in_money / $this->inventoryError->error
                       : 0,
            'error' => $this->inventoryError->error,
            'error_in_money' => $this->inventoryError->error_in_money,
            'created_at' => $this->created_at->format('d.m.Y H:i:s'),
        ];
    }
}

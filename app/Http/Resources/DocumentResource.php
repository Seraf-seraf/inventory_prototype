<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'type' => $this->type,
            'document_items' => DocumentItemResource::collection($this->whenLoaded('documentItems')),
            'created_at' => $this->created_at->format('d.m.Y H:i:s')
        ];
    }
}

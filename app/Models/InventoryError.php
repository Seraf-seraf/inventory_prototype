<?php

namespace App\Models;

use App\Services\DocumentService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryError extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'error'
    ];

    protected $appends = [
        'error_in_money'
    ];

    public function getErrorInMoneyAttribute(): float|int
    {
        return (float) $this->error * DocumentService::getCostForInventory($this->documentItem->product->id);
    }

    public function documentItem(): BelongsTo
    {
        return $this->belongsTo(DocumentItem::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}

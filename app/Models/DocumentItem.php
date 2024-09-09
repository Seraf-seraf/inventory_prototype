<?php

namespace App\Models;

use App\Services\DocumentService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DocumentItem extends Model
{
    protected $fillable = [
        'product_id',
        'remainder',
        'quantity',
        'price'
    ];

    protected $appends = [
        'remainder_in_money'
    ];

    protected $hidden = [
        'product'
    ];

    public function getRemainderInMoneyAttribute(): float
    {
        return $this->remainder * DocumentService::getCostForInventory($this->product_id);
    }

    public function inventoryError(): HasOne
    {
        return $this->hasOne(InventoryError::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}

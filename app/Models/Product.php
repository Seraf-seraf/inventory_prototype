<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{

    public function documentItems(): HasMany
    {
        return $this->hasMany(DocumentItem::class);
    }

    public function productDocuments(): HasManyThrough
    {
        return $this->hasManyThrough(Document::class, DocumentItem::class);
    }
}

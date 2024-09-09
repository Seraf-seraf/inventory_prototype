<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    public const string DOCUMENT_INCOME = 'income';
    public const string DOCUMENT_EXPENSE = 'expense';
    public const string DOCUMENT_INVENTORY = 'inventory';

    protected $fillable = [
        'type',
        'created_at'
    ];

    public function documentItems(): HasMany
    {
        return $this->hasMany(DocumentItem::class);
    }
}

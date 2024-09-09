<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;

class DocumentService
{
    public string $type;
    public array $products;
    public string $time;

    public function __construct(array $data)
    {
        $this->type = $data['type'];
        $this->products = $data['products'];
        $this->time = $data['created_at'] ?? now()->format('Y-m-d H:i:s');
    }

    protected function getRemainder(int $productId, int $quantity = 0): int
    {
        return match ($this->type) {
            Document::DOCUMENT_INCOME => Product::where(['id' => $productId])->value('count') + $quantity,
            Document::DOCUMENT_EXPENSE => Product::where(['id' => $productId])->value('count') - $quantity,
            Document::DOCUMENT_INVENTORY => $quantity
        };
    }

    protected function getInventoryError(int $productId, int $quantity = 0): int
    {
        return Product::where(['id' => $productId])->value('count') - $quantity;
    }

    public function createDocument(): void
    {
        DB::transaction(function () {
            $document = Document::create(['type' => $this->type]);

            foreach ($this->products as $productData) {
                $product = Product::where('id', $productData['product_id'])->lockForUpdate()->first();

                $remainder = $this->getRemainder($product->id, $productData['quantity']);

                $documentItem = $document->documentItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'remainder' => $remainder,
                    'price' => $this->type === Document::DOCUMENT_INCOME ? $productData['price'] : null,
                    'created_at' => $this->time
                ]);


                if ($this->type === Document::DOCUMENT_INVENTORY) {
                    $documentItem->inventoryError()->create([
                        'error' => $this->getInventoryError($product->id, $productData['quantity'])
                    ]);
                }

                $product->count = $remainder;
                $product->save();
            }
        });
    }

    public static function getCostForInventory(int $productId): float
    {
        $now = Carbon::now();
        $date20daysAgo = $now->copy()->subDays(20);

        $incomeReceipts = DocumentItem::join('documents', 'document_items.document_id', '=', 'documents.id')
            ->where('document_items.product_id', $productId)
            ->where('documents.type', Document::DOCUMENT_INCOME)
            ->whereBetween('documents.created_at', [$date20daysAgo, $now])
            ->select('document_items.price', 'document_items.quantity', 'documents.created_at')
            ->get();

        if ($incomeReceipts->isEmpty()) {
            $lastIncome = DocumentItem::join('documents', 'document_items.document_id', '=', 'documents.id')
                ->join('products', 'document_items.product_id', '=', 'products.id')
                ->where([
                    'products.id' => $productId,
                    'documents.type' => Document::DOCUMENT_INCOME
                ])
                ->select('document_items.price')
                ->latest('document_items.created_at')
                ->first();

            $price = $lastIncome ? $lastIncome->price : 0;
        } else {
            $price = $incomeReceipts->avg('price');
        }

        return $price;
    }
}

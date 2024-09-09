<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Http\Resources\InventoryResource;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Services\DocumentService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('documentItems.inventoryError')->paginate(10);

        return DocumentResource::collection($documents);
    }

    public function create(DocumentService $service)
    {
        $service->createDocument();

        return response()->json(['message' => 'Created!']);
    }

    public function productInventories(Request $request)
    {
        $latestDocumentItemsPaginator = DocumentItem::with(['inventoryError', 'document'])
            ->whereHas('document', function ($query) use ($request) {
                $type = Document::DOCUMENT_INVENTORY;
                $query->where('type', $type);

                if ($request->has('date')) {
                    $formattedDate =
                        Carbon::createFromFormat('d.m.Y', $request->get('date'))->format('Y-m-d');
                    $query->whereDate('created_at', $formattedDate);
                }
            })
            ->paginate(10);

        $latestDocumentItems = $latestDocumentItemsPaginator
            ->getCollection()
            ->groupBy('product_id')
            ->map(fn($items) => $items->sortByDesc('created_at')->first());

        $latestDocumentItemsPaginator->setCollection($latestDocumentItems);

        return InventoryResource::collection($latestDocumentItemsPaginator);
    }

}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'creator'])->latest()->get();
        return PurchaseOrderResource::collection($purchaseOrders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseOrderRequest $request)
    {
        $validatedData = $request->validated();

        $purchaseOrder = DB::transaction(function () use ($validatedData) {
            // Nomor permintaan barang
            $prefix = 'PO';
            $datePrefix = Carbon::now()->format('Ym');
            $count = PurchaseOrder::where('po_number', 'like', "{$prefix}-{$datePrefix}-%")->count();
            $nextNumber = $count + 1;
            $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $poNumber = "{$prefix}-{$datePrefix}-{$paddedNumber}";

            $po = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $validatedData['supplier_id'],
                'warehouse_id' => $validatedData['warehouse_id'],
                'order_date' => $validatedData['order_date'],
                'status' => 'Draft',
                'created_by' => auth()->id(),
            ]);

            $po->items()->createMany($validatedData['items']);

            return $po;
        });

        return (new PurchaseOrderResource($purchaseOrder->load(['supplier', 'items.material'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        return new PurchaseOrderResource($purchaseOrder->load(['supplier', 'warehouse', 'creator', 'items.material']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

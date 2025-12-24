<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleItemController extends Controller
{
    public function fromProduct(Request $request, $saleId)
    {
        $sale = Sale::where('id', $saleId)->firstOrFail();

        if ($sale->status !== 'open') {
            abort(422, 'Sale not open');
        }

        $request->validate([
            'product_id' => 'required|exists:tenant.products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $sale) {

            $product = Product::lockForUpdate()
                ->where('id', $request->product_id)
                ->firstOrFail();

            if ($product->stock < $request->quantity) {
    return redirect()
        ->back()
        ->withErrors([
            'stock' => "Stock insuficiente. Solo quedan {$product->stock} unidades."
        ])
        ->withInput();
}


            $unitPrice  = $product->pvp;
            $taxPercent = $product->porcentaje_impuesto;

            $item = SaleItem::where('sale_id', $sale->id)
                ->where('item_type', 'product')
                ->where('item_id', $product->id)
                ->first();

            if ($item) {
                $newQuantity = $item->quantity + $request->quantity;

                $base      = $unitPrice * $newQuantity;
                $taxAmount = $base * ($taxPercent / 100);
                $subtotal  = $base + $taxAmount;

                $item->update([
                    'quantity'   => $newQuantity,
                    'tax_amount' => $taxAmount,
                    'subtotal'   => $subtotal,
                ]);
            } else {
                $base      = $unitPrice * $request->quantity;
                $taxAmount = $base * ($taxPercent / 100);
                $subtotal  = $base + $taxAmount;

                SaleItem::create([
                    'sale_id'     => $sale->id,
                    'item_type'   => 'product',
                    'item_id'     => $product->id,
                    'name'        => $product->producto,
                    'quantity'    => $request->quantity,
                    'unit_price'  => $unitPrice,
                    'tax_percent' => $taxPercent,
                    'tax_amount'  => $taxAmount,
                    'subtotal'    => $subtotal,
                ]);
            }

            $product->decrement('stock', $request->quantity);

            $this->recalculateSale($sale);
        });

        return redirect()->route('tenant.sales.show', $sale->id);
    }

    public function destroy($saleId, $itemId)
    {
        $sale = Sale::where('id', $saleId)->firstOrFail();

        if ($sale->status !== 'open') {
            abort(422, 'Sale not open');
        }

        DB::transaction(function () use ($sale, $itemId) {

            $item = SaleItem::where('id', $itemId)
                ->where('sale_id', $sale->id)
                ->firstOrFail();

            if ($item->item_type === 'product' && $item->item_id) {
                Product::where('id', $item->item_id)
                    ->increment('stock', $item->quantity);
            }

            $item->delete();

            $this->recalculateSale($sale);
        });

        return redirect()->route('tenant.sales.show', $sale->id);
    }

    private function recalculateSale(Sale $sale)
    {
        $items = $sale->items;

        $sale->subtotal  = $items->sum(fn ($i) => $i->unit_price * $i->quantity);
        $sale->tax_total = $items->sum('tax_amount');
        $sale->total     = $sale->subtotal + $sale->tax_total;

        $sale->save();
    }
}

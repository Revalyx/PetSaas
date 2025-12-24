<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class SaleController extends Controller
{
    /**
     * LISTADO DE VENTAS ABIERTAS + BUSCADOR
     */
    public function index(Request $request)
    {
        $query = Sale::with('customer')
            ->where('status', 'open');

        if ($request->filled('q')) {
            $q = trim($request->q);

            $query->where(function ($sub) use ($q) {
                $sub->where('id', $q)
                    ->orWhereHas('customer', function ($c) use ($q) {
                        $c->where('nombre', 'like', "%{$q}%")
                          ->orWhere('apellidos', 'like', "%{$q}%")
                          ->orWhere('telefono', 'like', "%{$q}%");
                    });
            });
        }

        $sales = $query
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tenant.sales.index', compact('sales'));
    }

    /**
     * CREAR NUEVA VENTA
     */
    public function store()
    {
        $sale = Sale::create([
            'tenant_id'   => auth()->user()->tenant_id,
            'employee_id' => auth()->id(),
            'status'      => 'open',
            'subtotal'    => 0,
            'tax_total'   => 0,
            'total'       => 0,
        ]);

        return redirect()->route('tenant.sales.show', $sale->id);
    }

    /**
     * MOSTRAR VENTA (TPV)
     */
    public function show($saleId)
    {
        $sale = Sale::where('id', $saleId)->firstOrFail();
        $sale->load('items', 'customer');

        // 🔒 Si está cerrada → vista solo lectura
        if ($sale->status === 'closed') {
            return view('tenant.sales.closed', compact('sale'));
        }

        $products = Product::orderBy('producto')->get();
        $clientes = Cliente::orderBy('nombre')->get();

        return view('tenant.sales.show', compact('sale', 'products', 'clientes'));
    }

    /**
     * ASIGNAR / CAMBIAR CLIENTE
     */
    public function assignClient(Request $request, $saleId)
    {
        $sale = Sale::where('id', $saleId)->firstOrFail();

        if ($sale->status !== 'open') {
            abort(422, 'Sale not open');
        }

        $request->validate([
            'customer_id' => 'nullable|exists:tenant.clientes,id',
        ]);

        $sale->customer_id = $request->customer_id;
        $sale->save();

        return redirect()->back();
    }

    /**
     * ❗❗ AQUÍ VA EL MÉTODO CLOSE ❗❗
     * CERRAR / COBRAR VENTA
     */
    public function close($saleId)
    {

        $sale = Sale::where('id', $saleId)->firstOrFail();

        if ($sale->status !== 'open') {
            abort(422, 'Sale already closed');
        }

        if ($sale->items()->count() === 0) {
            abort(422, 'Cannot close empty sale');
        }

        DB::transaction(function () use ($sale) {
            $sale->status    = 'closed';
            $sale->closed_at = now();
            $sale->save();
        });

        return redirect()
    ->route('tenant.sales.show', $sale->id)
    ->with('success', 'Venta cerrada correctamente');
    }

public function invoice($saleId)
{
    // AHORA el tenant middleware YA está activo
    $sale = Sale::where('id', $saleId)->firstOrFail();

    abort_unless($sale->closed_at, 404);

    $sale->load(['items', 'customer']);

    $tenant = auth()->user()->tenant;

    $pdf = Pdf::loadView('tenant.sales.invoice', [
        'sale'   => $sale,
        'tenant' => $tenant,
    ])->setPaper('A4');

    return $pdf->download('factura_'.$sale->id.'.pdf');
}

    /**
     * CANCELAR / ELIMINAR VENTA ABIERTA
     */
    public function destroy($saleId)
    {
        $sale = Sale::where('id', $saleId)->firstOrFail();

        if ($sale->status !== 'open') {
            abort(422, 'Only open sales can be cancelled');
        }

        DB::transaction(function () use ($sale) {

            foreach ($sale->items as $item) {
                if ($item->item_type === 'product' && $item->item_id) {
                    Product::where('id', $item->item_id)
                        ->increment('stock', $item->quantity);
                }
            }

            $sale->items()->delete();
            $sale->delete();
        });

        return redirect()
    ->route('tenant.sales.show', $sale->id)
    ->with('success', 'Venta cerrada correctamente');

    }
}

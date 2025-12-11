<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function getTenant()
    {
        $user = auth()->user();

        if (!$user || !$user->tenant_id) {
            abort(403, "Usuario sin tenant asignado.");
        }

        $tenant = Tenant::find($user->tenant_id);

        if (!$tenant) {
            abort(500, "No se pudo resolver el tenant.");
        }

        return $tenant;
    }

    public function index()
    {
        $products = Product::all();
        return view('tenant.products.index', compact('products'));
    }

    public function create()
    {
        return view('tenant.products.create');
    }

    public function store(Request $request)
    {
        $tenant = $this->getTenant();

        $request->validate([
            'id_adicional'        => 'nullable|string|max:50',
            'codigo_barras'       => 'nullable|string|max:50',
            'categoria'           => 'nullable|string|max:100',
            'producto'            => 'required|string|max:255',

            'precio'              => 'required|numeric|min:0|max:999999.99',
            'porcentaje_impuesto' => 'required|numeric|min:0|max:100',
            'pvp'                 => 'nullable|numeric|min:0|max:999999.99',
            'beneficio'           => 'nullable|numeric|min:0|max:999999.99',
            'margen'              => 'nullable|numeric|min:0|max:999999.99',
            'stock'               => 'required|integer|min:0',

            'image'               => 'nullable|image|mimes:jpg,png,jpeg,webp|max:4096',
            'image_alt'           => 'nullable|string|max:255'
        ]);

        $precio = $request->precio;
        $impuesto = $request->porcentaje_impuesto;

        // QUALITY OF LIFE: cálculos automáticos
        $precio_real = $precio + ($precio * ($impuesto / 100));

        $pvp = $request->pvp ?: $precio_real;
        $beneficio = $request->beneficio ?: ($pvp - $precio_real);
        $margen = $request->margen ?: (($beneficio > 0 && $precio_real > 0) ? ($beneficio / $precio_real) * 100 : 0);

        $data = [
            'id_adicional'        => $request->id_adicional,
            'codigo_barras'       => $request->codigo_barras,
            'categoria'           => $request->categoria,
            'producto'            => $request->producto,

            'precio'              => $precio,
            'porcentaje_impuesto' => $impuesto,
            'precio_real'         => $precio_real,
            'pvp'                 => $pvp,
            'beneficio'           => $beneficio,
            'margen'              => $margen,
            'stock'               => $request->stock,
            'image_alt'           => $request->image_alt,
        ];

        // Asegurar directorio del tenant
        Storage::disk('public')->makeDirectory("tenants/{$tenant->slug}/products");

        // Subida de imagen
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store(
                "tenants/{$tenant->slug}/products",
                'public'
            );
        }

        Product::create($data);

        return redirect()
            ->route('tenant.products.index')
            ->with('ok', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('tenant.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $tenant = $this->getTenant();

        $request->validate([
            'id_adicional'        => 'nullable|string|max:50',
            'codigo_barras'       => 'nullable|string|max:50',
            'categoria'           => 'nullable|string|max:100',
            'producto'            => 'required|string|max:255',

            'precio'              => 'required|numeric|min:0|max:999999.99',
            'porcentaje_impuesto' => 'required|numeric|min:0|max:100',
            'pvp'                 => 'nullable|numeric|min:0|max:999999.99',
            'beneficio'           => 'nullable|numeric|min:0|max:999999.99',
            'margen'              => 'nullable|numeric|min:0|max:999999.99',
            'stock'               => 'required|integer|min:0',

            'image'               => 'nullable|image|mimes:jpg,png,jpeg,webp|max:4096',
            'image_alt'           => 'nullable|string|max:255'
        ]);

        $product = Product::findOrFail($id);

        $precio = $request->precio;
        $impuesto = $request->porcentaje_impuesto;

        // Recalcular
        $precio_real = $precio + ($precio * ($impuesto / 100));
        $pvp = $request->pvp ?: $precio_real;
        $beneficio = $request->beneficio ?: ($pvp - $precio_real);
        $margen = $request->margen ?: (($beneficio > 0 && $precio_real > 0) ? ($beneficio / $precio_real) * 100 : 0);

        $data = [
            'id_adicional'        => $request->id_adicional,
            'codigo_barras'       => $request->codigo_barras,
            'categoria'           => $request->categoria,
            'producto'            => $request->producto,

            'precio'              => $precio,
            'porcentaje_impuesto' => $impuesto,
            'precio_real'         => $precio_real,
            'pvp'                 => $pvp,
            'beneficio'           => $beneficio,
            'margen'              => $margen,
            'stock'               => $request->stock,
            'image_alt'           => $request->image_alt,
        ];

        // Asegurar directorio
        Storage::disk('public')->makeDirectory("tenants/{$tenant->slug}/products");

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store(
                "tenants/{$tenant->slug}/products",
                'public'
            );
        }

        $product->update($data);

        return redirect()
            ->route('tenant.products.index')
            ->with('ok', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
{
    $tenant = $this->getTenant();
    $product = Product::findOrFail($id);

    // ELIMINAR IMAGEN SI EXISTE
    if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
        Storage::disk('public')->delete($product->image_path);
    }

    // ELIMINAR PRODUCTO
    $product->delete();

    return redirect()
        ->route('tenant.products.index')
        ->with('ok', 'Producto eliminado correctamente.');
}

}

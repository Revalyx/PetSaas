<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Obtener tenant SIEMPRE desde el usuario autenticado.
     */
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
        \Log::info("PRODUCT STORE START", [
            'user'      => auth()->user(),
            'tenant_id' => auth()->user()->tenant_id ?? null
        ]);

        $tenant = $this->getTenant();

        \Log::info("PRODUCT STORE TENANT OK", [
            'slug' => $tenant->slug
        ]);

        /**
         * VALIDACIÓN MEJORADA
         */
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0|max:999999.99',
            'stock'       => 'required|integer|min:0|max:999999',
            'barcode'     => 'nullable|digits_between:8,13',  // SOLO NÚMEROS
            'image'       => 'nullable|image|mimes:jpg,png,jpeg,webp|max:4096',
        ]);

        $data = $request->only(['name', 'description', 'price', 'stock', 'barcode']);

        /**
         * SUBIDA DE IMAGEN SEGURA
         */
        if ($request->hasFile('image')) {
            try {
                $data['image_path'] = $request->file('image')->store(
                    "tenants/{$tenant->slug}/products",
                    'public'
                );
            } catch (\Exception $e) {
                \Log::error("ERROR SUBIENDO IMAGEN: " . $e->getMessage());
                return back()->withErrors("Error subiendo la imagen. Inténtelo de nuevo.");
            }
        }

        Product::create($data);

        \Log::info("Producto creado OK", $data);

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

        /**
         * VALIDACIÓN MEJORADA
         */
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0|max:999999.99',
            'stock'       => 'required|integer|min:0|max:999999',
            'barcode'     => 'nullable|digits_between:8,13',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg,webp|max:4096',
        ]);

        $product = Product::findOrFail($id);

        $data = $request->only(['name', 'description', 'price', 'stock', 'barcode']);

        /**
         * SUBIDA DE IMAGEN SEGURA
         */
        if ($request->hasFile('image')) {
            try {
                $data['image_path'] = $request->file('image')->store(
                    "tenants/{$tenant->slug}/products",
                    'public'
                );
            } catch (\Exception $e) {
                \Log::error("ERROR SUBIENDO IMAGEN: " . $e->getMessage());
                return back()->withErrors("Error subiendo la imagen. Inténtelo de nuevo.");
            }
        }

        $product->update($data);

        return redirect()
            ->route('tenant.products.index')
            ->with('ok', 'Producto actualizado correctamente.');
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()
            ->route('tenant.products.index')
            ->with('ok', 'Producto eliminado correctamente.');
    }
}

<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = Product::with(['size', 'category', 'material']);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%'); // Search by product name
        }

        // Paginate the results
        $products = $query->paginate(10);

        // Transform the products data
        $final = [];
        foreach ($products->items() as $product) {
            $final[] = [
                'id' => $product->id,
                'name' => $product->name, // Changed to name
                'description' => $product->description,
                'size' => $product->size ? $product->size->name : null,
                'category' => $product->category ? $product->category->name : null,
                'material' => $product->material ? $product->material->name : null,
                'color' => $product->color,
                'unit_price' => $product->unit_price,
                'image_url' => $product->image_url,
            ];
        }

        // Set transformed data back to paginator
        $products->setCollection(collect($final));

        // Return paginated response with search results
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string', // Changed to name
            'description' => 'nullable|string',
            'size_id' => 'required|uuid|exists:sizes,id',
            'unit_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|uuid|exists:categories,id',
            'color' => 'required|string',
            'material_id' => 'nullable|uuid|exists:materials,id',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name, // Changed to name
            'description' => $request->description,
            'size_id' => $request->size_id,
            'unit_price' => $request->unit_price,
            'category_id' => $request->category_id,
            'color' => $request->color,
            'material_id' => $request->material_id,
            'image_url' => $request->image_url,
        ]);

        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with(['size', 'category', 'material'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $product->id,
                'name' => $product->name, // Changed to name
                'description' => $product->description,
                'size' => $product->size ? $product->size->name : null,
                'category' => $product->category ? $product->category->name : null,
                'material' => $product->material ? $product->material->name : null,
                'color' => $product->color,
                'unit_price' => $product->unit_price,
                'image_url' => $product->image_url,
            ]
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string', // Changed to name
            'description' => 'nullable|string',
            'size_id' => 'sometimes|required|uuid|exists:sizes,id',
            'unit_price' => 'sometimes|required|numeric|min:0',
            'category_id' => 'nullable|uuid|exists:categories,id',
            'color' => 'sometimes|required|string',
            'material_id' => 'nullable|uuid|exists:materials,id',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($request->all());

        return response()->json($product);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}

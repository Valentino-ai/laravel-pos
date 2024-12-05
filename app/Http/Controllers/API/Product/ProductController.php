<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Product::with(['size', 'category', 'material']);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->paginate(5);

        $final = [];
        foreach ($products->items() as $product) {
            $final[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'size' => $product->size ? $product->size->name : null,
                'category' => $product->category ? $product->category->name : null,
                'material' => $product->material ? $product->material->name : null,
                'color' => $product->color,
                'unit_price' => $product->unit_price,
                'image_url' => $product->image_url,
            ];
        }

        $products->setCollection(collect($final));

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'size_id' => 'required|uuid|exists:sizes,id',
            'unit_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|uuid|exists:categorys,id',
            'color' => 'required|string',
            'material_id' => 'nullable|uuid|exists:materials,id',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $path = $image->store('products', 'public');
            $publicPath = url("storage/{$path}");
        } else {
            return response()->json(['message' => 'No file received'], 400);
        }

        $product = Product::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'description' => $request->description,
            'size_id' => $request->size_id,
            'unit_price' => $request->unit_price,
            'category_id' => $request->category_id,
            'color' => $request->color,
            'material_id' => $request->material_id,
            'image_url' => $publicPath,
        ]);

        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::with(['size', 'category', 'material'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
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

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'size_id' => 'required|exists:sizes,id',
            'category_id' => 'required|exists:categorys,id',
            'material_id' => 'nullable|exists:materials,id',
            'color' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validatedData);

        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('products', 'public');
            $product->image_url = url("storage/{$path}");
            $product->save();
        }

        return response()->json(['data' => $product, 'message' => 'Product updated successfully'], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}

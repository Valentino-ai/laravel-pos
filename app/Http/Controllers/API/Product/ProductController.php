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
        
        $query = Product::with(['size', 'categorys', 'material']);

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
                'categorys' => $product->categorys ? $product->categorys->name : null,
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
            'categorys_id' => 'nullable|uuid|exists:categorys,id',
            'color' => 'required|string',
            'material_id' => 'nullable|uuid|exists:materials,id',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'description' => $request->description,
            'size_id' => $request->size_id,
            'unit_price' => $request->unit_price,
            'categorys_id' => $request->categorys_id,
            'color' => $request->color,
            'material_id' => $request->material_id,
            'image_url' => $request->image_url,
        ]);

        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::with(['size', 'categorys', 'material'])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'size' => $product->size ? $product->size->name : null,
                'categorys' => $product->categorys ? $product->categorys->name : null,
                'material' => $product->material ? $product->material->name : null,
                'color' => $product->color,
                'unit_price' => $product->unit_price,
                'image_url' => $product->image_url,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'size_id' => 'sometimes|required|uuid|exists:sizes,id',
            'unit_price' => 'sometimes|required|numeric|min:0',
            'categorys_id' => 'nullable|uuid|exists:categorys,id',
            'color' => 'sometimes|required|string',
            'material_id' => 'nullable|uuid|exists:materials,id',
            'image_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update([
            ...$request->except('categorys_id'),
            'categorys_id' => $request->categorys_id,
        ]);

        return response()->json($product);
    }

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

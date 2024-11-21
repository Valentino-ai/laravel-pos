<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    /**
     * Display a listing of the materials.
     */
    public function index()
    {
        // Retrieve all materials
        $materials = Material::all();
        return response()->json(['materials' => $materials]);
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        $material = Material::create([
            'id' => Str::uuid(),
            'name' => $request->name,
            'stock' => $request->stock,
        ]);

        return response()->json(['message' => 'Material created successfully', 'material' => $material], 201);
    }

    /**
     * Display the specified material.
     */
    public function show($id)
    {
        $material = Material::findOrFail($id);
        return response()->json(['material' => $material]);
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'stock' => 'sometimes|required|integer|min:0',  
        ]);

        // Find and update the material
        $material = Material::findOrFail($id);
        $material->update($request->only(['name', 'stock']));

        return response()->json(['message' => 'Material updated successfully', 'material' => $material]);
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return response()->json(['message' => 'Material deleted successfully']);
    }
}

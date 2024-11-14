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
            'stock' => 'required|integer|min:0',  // Stock validation
        ]);

        // Create new material with UUID and stock
        $material = Material::create([
            'id' => Str::uuid(),  // Generate UUID for the material
            'name' => $request->name,
            'stock' => $request->stock,  // Add stock value
        ]);

        return response()->json(['message' => 'Material created successfully', 'material' => $material], 201);
    }

    /**
     * Display the specified material.
     */
    public function show($id)
    {
        // Retrieve material by ID
        $material = Material::findOrFail($id);
        return response()->json(['material' => $material]);
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate input (only fields that are passed in the request)
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'stock' => 'sometimes|required|integer|min:0',  // Stock validation for updates
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
        // Find and delete the material
        $material = Material::findOrFail($id);
        $material->delete();

        return response()->json(['message' => 'Material deleted successfully']);
    }
}

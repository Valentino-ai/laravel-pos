<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Get all categorys.
     */
    public function index()
    {
        $categorys = Category::all(); // Fetch all categorys
        return response()->json(['categorys' => $categorys]); // Return 'categorys' for consistency with frontend
    }

    /**
     * Create a new category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'id' => Str::uuid(),
            'name' => $request->name,
        ]);

        return response()->json(['categorys' => $category], 201); // Return 'categorys' for a single resource
    }

    /**
     * Get a single category by ID.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id); // Automatically returns 404 if not found

        return response()->json(['categorys' => $category]); // Return 'categorys' for single resource
    }

    /**
     * Update a category.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id); // Automatically returns 404 if not found

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json(['categorys' => $category]); // Return updated categorys
    }

    /**
     * Delete a category.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id); // Automatically returns 404 if not found

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}

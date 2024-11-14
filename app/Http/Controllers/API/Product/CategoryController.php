<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Get all categories.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories]);
    }

    /**
     * Create a new category.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create category
        $category = Category::create([
            'id' => Str::uuid(),
            'name' => $request->name,
        ]);

        return response()->json(['category' => $category], 201);
    }

    /**
     * Get a single category by ID.
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json(['category' => $category]);
    }

    /**
     * Update a category.
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update category
        $category->update([
            'name' => $request->name,
        ]);

        return response()->json(['category' => $category]);
    }

    /**
     * Delete a category.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Delete category
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}

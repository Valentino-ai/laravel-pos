<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    /**
     * Display a listing of sizes.
     */
    public function index()
    {
        $sizes = Size::all();
        return response()->json(['sizes' => $sizes], 200);
    }

    /**
     * Store a newly created size in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $size = Size::create([
            'name' => $request->name,
        ]);

        return response()->json(['size' => $size, 'message' => 'Size created successfully'], 201);
    }

    /**
     * Display the specified size.
     */
    public function show($id)
    {
        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Size not found'], 404);
        }
        return response()->json(['size' => $size], 200);
    }

    /**
     * Update the specified size in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $size = Size::find($id);

    //     if (!$size) {
    //         return response()->json(['message' => 'Size not found'], 404);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $size->update([
    //         'name' => $request->name,
    //     ]);

    //     return response()->json(['size' => $size, 'message' => 'Size updated successfully'], 200);
    // }

    /**
     * Remove the specified size from storage.
     */
    public function destroy($id)
    {
        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Size not found'], 404);
        }

        $size->delete();

        return response()->json(['message' => 'Size deleted successfully'], 200);
    }
}

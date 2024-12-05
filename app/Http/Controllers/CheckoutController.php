<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Show available products for checkout.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableProducts(Request $request)
    {
        $search = $request->query('search');
        $query = \App\Models\Product::with(['size', 'category', 'material']);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->get();

        $final = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'size' => $product->size->name ?? null,
                'category' => $product->category->name ?? null,
                'material' => $product->material->name ?? null,
                'color' => $product->color,
                'unit_price' => $product->unit_price,
                'image_url' => $product->image_url,
            ];
        });

        return response()->json($final);
    }

    /**
     * Create a new checkout session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.product_id' => 'required|uuid|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $checkoutCode = Checkout::generateUniqueCode();

        $products = collect($request->products)->map(function ($item) {
            $product = \App\Models\Product::findOrFail($item['product_id']);
            return [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->unit_price,
            ];
        });

        $checkout = Checkout::create([
            'code' => $checkoutCode,
            'customer_name' => $request->customer_name,
            'products' => $products->toJson(),
        ]);

        return response()->json([
            'message' => 'Checkout created successfully',
            'checkout' => $checkout,
        ], 201);
    }

    /**
     * Update the quantity of a product in the checkout.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $checkoutId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCheckoutQuantity(Request $request, $checkoutId)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|uuid|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $checkout = Checkout::findOrFail($checkoutId);
        $products = collect(json_decode($checkout->products, true));

        $products = $products->map(function ($product) use ($request) {
            if ($product['product_id'] == $request->product_id) {
                $product['quantity'] = $request->quantity;
            }
            return $product;
        });

        $checkout->products = $products->toJson();
        $checkout->save();

        return response()->json([
            'message' => 'Checkout updated successfully',
            'checkout' => $checkout,
        ]);
    }

    /**
     * Get the details of a specific checkout.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCheckoutDetails($id)
    {
        $checkout = Checkout::with('products.product')->findOrFail($id);

        return response()->json(['checkout' => $checkout]);
    }

    /**
     * Get a list of checkout history for a specific customer.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCheckoutHistory(Request $request)
    {
        $customerName = $request->query('customer_name');
        $dateRangeStart = $request->query('start_date');
        $dateRangeEnd = $request->query('end_date');

        $query = Checkout::query();

        if ($customerName) {
            $query->where('customer_name', 'like', '%' . $customerName . '%');
        }

        if ($dateRangeStart && $dateRangeEnd) {
            $query->whereBetween('created_at', [$dateRangeStart, $dateRangeEnd]);
        } elseif ($dateRangeStart) {
            $query->where('created_at', '>=', $dateRangeStart);
        } elseif ($dateRangeEnd) {
            $query->where('created_at', '<=', $dateRangeEnd);
        }

        $checkouts = $query->get();

        $history = $checkouts->map(function ($checkout) {
            $products = json_decode($checkout->products, true);
            $totalAmount = collect($products)->sum(function ($product) {
                return $product['unit_price'] * $product['quantity'];
            });

            return [
                'checkout_code' => $checkout->code,
                'customer_name' => $checkout->customer_name,
                'total_amount' => $totalAmount,
                'created_at' => $checkout->created_at->toDateTimeString(),
                'products' => $products,
            ];
        });

        return response()->json(['checkouts' => $history]);
    }

    /**
     * Delete a specific checkout.
     *
     * @param string $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($code)
    {
        $checkout = Checkout::where('code', $code)->first();

        if (!$checkout) {
            return response()->json(['message' => 'Checkout not found'], 404);
        }

        $checkout->delete();

        return response()->json(['message' => 'Checkout deleted successfully'], 200);
    }
}

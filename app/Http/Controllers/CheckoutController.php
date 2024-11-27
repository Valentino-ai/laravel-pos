<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Create a new checkout.
     */
    public function createCheckout(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $totalAmount = 0;

        // Calculate total amount
        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $totalAmount += $product->unit_price * $productData['quantity'];
        }

        // Create the checkout
        $checkout = Checkout::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
        ]);

        // Add products to checkout details
        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            CheckoutDetail::create([
                'checkout_id' => $checkout->id,
                'product_id' => $product->id,
                'quantity' => $productData['quantity'],
                'unit_price' => $product->unit_price,
                'subtotal' => $product->unit_price * $productData['quantity'],
            ]);
        }

        return response()->json([
            'message' => 'Checkout created successfully.',
            'checkout' => $checkout->load('checkoutDetails'),
        ]);
    }

    /**
     * Get details of a specific checkout.
     */
    public function getCheckout($id)
    {
        $checkout = Checkout::with('checkoutDetails.product')->findOrFail($id);

        return response()->json([
            'checkout' => $checkout,
        ]);
    }

    /**
     * Get all checkouts for the authenticated user.
     */
    public function getUserCheckouts()
    {
        $user = Auth::user();
        $checkouts = Checkout::where('user_id', $user->id)->with('checkoutDetails.product')->get();

        return response()->json([
            'checkouts' => $checkouts,
        ]);
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,completed,failed',
        ]);

        $checkout = Checkout::findOrFail($id);
        $checkout->update([
            'payment_status' => $request->payment_status,
        ]);

        return response()->json([
            'message' => 'Payment status updated successfully.',
            'checkout' => $checkout,
        ]);
    }
}

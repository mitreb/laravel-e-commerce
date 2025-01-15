<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function addToCart(Request $request, Product $product): JsonResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        try {
            $cartItem = $this->cartService->addToCart(
                $product,
                $request->quantity,
                auth()->id()
            );

            return response()->json([
                'message' => 'Product added to cart',
                'cart_item' => $cartItem
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

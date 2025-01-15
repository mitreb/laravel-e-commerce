<?php

namespace App\Services;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InsufficientStockException;

class CartService
{
    public function addToCart(Product $product, int $quantity, ?string $userId = null): CartItem
    {
        return DB::transaction(function () use ($product, $quantity, $userId) {
            if (!$product->isInStock($quantity)) {
                throw new InsufficientStockException("Not enough stock available");
            }

            $cartItem = CartItem::updateOrCreate(
                [
                    'user_id' => $userId,
                    'session_id' => $userId ? null : session()->getId(),
                    'product_id' => $product->id,
                ],
                ['quantity' => DB::raw("quantity + $quantity")]
            );

            $product->decrementStock($quantity);

            return $cartItem;
        });
    }
}

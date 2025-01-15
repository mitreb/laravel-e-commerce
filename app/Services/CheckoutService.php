<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function process(array $data, ?string $discountCode = null): Order
    {
        return DB::transaction(function () use ($data, $discountCode) {
            // Get or create user
            $user = $this->getOrCreateUser($data);

            // Get cart items
            $cartItems = CartItem::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('session_id', session()->getId());
            })->with('product')->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            // Calculate totals
            $subtotal = $cartItems->sum(
                fn($item) =>
                $item->product->price * $item->quantity
            );

            // Apply discount if valid code provided
            $discountAmount = 0;
            if ($discountCode) {
                $discountAmount = 5.00; // Fixed discount amount as per requirements
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $subtotal - $discountAmount,
                'discount_code' => $discountCode,
                'discount_amount' => $discountAmount,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Clear cart
            $cartItems->each->delete();

            // TODO: Dispatch job to generate discount code here

            return $order->load('items.product');
        });
    }

    private function getOrCreateUser(array $data): User
    {
        // For logged-in users, use the authenticated user
        if (auth()->check()) {
            $user = auth()->user();

            // Update address if provided
            $user->update([
                'address' => $data['address'],
                'city' => $data['city'],
                'postal_code' => $data['postal_code'],
                'phone' => $data['phone'],
            ]);

            return $user;
        }

        // For guests, create new user
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'address' => $data['address'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'phone' => $data['phone'],
        ]);
    }
}

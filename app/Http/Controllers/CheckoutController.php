<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    public function __construct(
        private CheckoutService $checkoutService
    ) {}

    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            // Only required for guests
            'name' => [auth()->guest() ? 'required' : 'nullable', 'string'],
            'email' => [auth()->guest() ? 'required' : 'nullable', 'email'],
            'password' => [auth()->guest() ? 'required' : 'nullable', 'min:8'],

            // Required for everyone
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'phone' => 'required|string',
            'discount_code' => 'nullable|string|exists:discount_codes,code,is_used,0',
        ]);

        try {
            $order = $this->checkoutService->process(
                $request->all(),
                $request->discount_code
            );

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}

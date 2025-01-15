<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\DiscountCode;
use App\Notifications\DiscountCodeGenerated;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;

class GenerateDiscountCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private User $user
    ) {}

    public function handle(): void
    {
        // Create discount code
        $discountCode = DiscountCode::create([
            'user_id' => $this->user->id,
            'code' => $this->generateUniqueCode(),
            'amount' => 5.00,
            'expires_at' => now()->addDays(30),
        ]);

        // Send notification
        $this->user->notify(new DiscountCodeGenerated($discountCode));
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = 'DISCOUNT-' . Str::random(8);
        } while (DiscountCode::where('code', $code)->exists());

        return $code;
    }
}

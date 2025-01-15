<?php

return [
    'default_amount' => env('DISCOUNT_DEFAULT_AMOUNT', 5.00),
    'expiry_days' => env('DISCOUNT_EXPIRY_DAYS', 30),
    'generation_delay_minutes' => env('DISCOUNT_GENERATION_DELAY', 15),
];

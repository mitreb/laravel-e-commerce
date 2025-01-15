<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Discount Code Settings
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for discount codes including
    | prefix, length, default amount and expiry period.
    |
    */

    // Prefix for all discount codes
    'prefix' => 'DISCOUNT-',

    // Length of the random portion of the discount code
    'code_length' => 8,

    // Default discount amount in currency units
    'default_amount' => 5.00,

    // Number of days until discount codes expire
    'expiry_days' => 30,
];

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'stock_quantity'];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    public function isInStock(int $quantity = 1): bool
    {
        return $this->stock_quantity >= $quantity;
    }

    public function decrementStock(int $quantity): void
    {
        if (!$this->isInStock($quantity)) {
            throw new \Exception('Not enough stock');
        }

        $this->decrement('stock_quantity', $quantity);
    }
}

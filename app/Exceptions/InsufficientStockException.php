<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    public function render($request)
    {
        return $request->expectsJson()
            ? response()->json(['error' => $this->getMessage()], 422)
            : back()->withErrors(['stock' => $this->getMessage()]);
    }
}

<?php

namespace App\Exceptions\Wallet;

use RuntimeException;

class InsufficientBalanceException extends RuntimeException
{
    public function __construct(float $required, float $available)
    {
        parent::__construct(
            "الرصيد غير كافٍ. المطلوب: \${$required}، المتاح: \${$available}"
        );
    }
}

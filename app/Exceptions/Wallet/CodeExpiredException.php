<?php

namespace App\Exceptions\Wallet;

use RuntimeException;

class CodeExpiredException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('انتهت صلاحية هذا الكود ولم يعد صالحاً للاستخدام.');
    }
}

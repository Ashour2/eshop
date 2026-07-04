<?php

namespace App\Exceptions\Wallet;

use RuntimeException;

class CodeNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('كود الشحن غير موجود. تحقق من الكود وأعد المحاولة.');
    }
}

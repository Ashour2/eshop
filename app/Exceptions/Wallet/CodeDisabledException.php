<?php

namespace App\Exceptions\Wallet;

use RuntimeException;

class CodeDisabledException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('هذا الكود معطّل ولا يمكن استخدامه.');
    }
}

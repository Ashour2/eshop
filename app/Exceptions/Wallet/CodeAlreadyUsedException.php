<?php

namespace App\Exceptions\Wallet;

use RuntimeException;

class CodeAlreadyUsedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('هذا الكود تم استخدامه مسبقاً ولا يمكن إعادة استخدامه.');
    }
}

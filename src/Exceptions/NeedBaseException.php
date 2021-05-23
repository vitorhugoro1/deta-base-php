<?php

namespace VitorHugoRo\Deta\Exceptions;

use Exception;

class NeedBaseException extends Exception
{
    public static function notHasBase()
    {
        throw new NeedBaseException('Not has provided an base name.');
    }
}

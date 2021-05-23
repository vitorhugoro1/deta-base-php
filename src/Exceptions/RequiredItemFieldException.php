<?php

namespace VitorHugoRo\Deta\Exceptions;

use Exception;

class RequiredItemFieldException extends Exception
{
    public static function missing(string $field)
    {
        throw new RequiredItemFieldException("Not found field {$field} on response.", 1);
    }
}

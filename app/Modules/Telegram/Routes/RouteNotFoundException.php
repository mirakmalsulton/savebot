<?php

namespace App\Src\Routes;

use Exception;
use JetBrains\PhpStorm\Pure;

class RouteNotFoundException extends Exception
{
    #[Pure] public function __construct($message = 'Route not found', $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

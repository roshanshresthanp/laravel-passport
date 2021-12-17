<?php

namespace App\Exceptions;

use Exception;

class ProductNotFound extends Exception
{
    public function render()
    {
        return ['message'=>'Product not found'];
    }
}

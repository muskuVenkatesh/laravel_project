<?php

namespace App\Exceptions;

use Log;
use Exception;

class DataNotFoundException extends Exception
{
    public function __construct($message = "Data not found")
    {
        parent::__construct($message);
    }

    // Optionally, you can define a report method for logging
    public function report()
    {
        Log::error($this->getMessage());
    }

    // Optionally, customize the response when the exception is thrown
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 404);
    }
}

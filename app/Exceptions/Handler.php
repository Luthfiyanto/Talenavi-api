<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\ApplicationException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if ($e instanceof ApplicationException && $request->expectsJson()) {
            return response()->json([
                'success' =>  false,
                'message' => $e->getMessage(),
                'errors' => $e->errors,
            ], $e->statusCode);
        }
        return parent::render($request, $e);
    }
}

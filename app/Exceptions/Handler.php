<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
{
    if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
        return response()->json(['success' => false, 'message' => 'Token tidak valid'], 401);
    }

    if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
        return response()->json(['success' => false, 'message' => 'Token kadaluarsa'], 401);
    }

    if ($exception instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
        return response()->json(['success' => false, 'message' => 'Token tidak ada'], 401);
    }

    return parent::render($request, $exception);
}
protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
{
    return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
}


    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}

<?php

namespace App\Exceptions;

use App\Exceptions\DBException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
        $this->renderable(function (NotFoundHttpException $e) {
            $message = $e->getMessage();
            if (empty($message)) {
                $message = "Not found";
            }
            return response()->json(["error" => $message], 404);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json(["error" => $e->getMessage()], 405);
        });

        $this->renderable(function (ValidationException $e) {
            return response()->json(["error" => $e->getMessage(), "details" => $e->validator->messages()->messages()], 422);
        });

        $this->renderable(function (DBException $e) {
            return response()->json(["error" => $e->getMessage()], 503);
        });
    }
}

<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        //not found exception controll
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['ex_message' => 'Record not found.','type' => 'NotFoundHttpException'], 404);
            }
        });
        //route not found exception controll
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['ex_message' => 'Invalid Route or method.' ,'type' => 'MethodNotAllowedHttpException'], 404);
            }
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['ex_message' => 'Model not found.' ,'type' => 'ModelNotFoundException' , 'line' =>$e->getLine() ], 404);
            }
        });

    }

    // public function report(Throwable $exception)
    // {
    //     if (app()->bound('sentry') && $this->shouldReport($exception)) {
    //         app('sentry')->captureException($exception);
    //     }

    //     parent::report($exception);
    // }
}

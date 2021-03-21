<?php

namespace App\Exceptions;

use Throwable;
use App\Exceptions\CustomException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
            //dd($e->getMessage());

            //return false;
        });//->stop();

        /*$this->renderable(function (Throwable $e, $request) {
            return response()->view('errors.invalid-order', [], 500);
        });*/
    }

    /**
     * Get the default context variables for logging.
     *
     * @return array
     */
    /*protected function context()
    {
        return array_merge(parent::context(), [
            'foo' => 'bar',
        ]);
    }*/
}

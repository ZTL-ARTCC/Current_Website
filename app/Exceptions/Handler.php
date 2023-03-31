<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler {
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception) {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception) {
        if ($exception->getStatusCode() == 500) {
            return $this->safeErrorMessage($exception);
        }
        return parent::render($request, $modifiedException);
    }

    /**
     * Checks exception messages for POST/GET variables and removes them. Prevents dangerous things like environment variables
     * from leaking while ensuring a meaningful and safe message can be displayed to the user (and reported to the dev team).
     */
    protected function safeErrorMessage($exception) {
        $safeErrorMessage = 'Unspecified error';
        $exceptionMessage = $exception->getMessage();
        if (preg_match('/(https?:\/\/.*[\?\&])/', $exceptionMessage)) {
            $safeErrorMessage = preg_replace('/(https?:\/\/.*[\?\&][^\s]+)/', '---', $exceptionMessage);
        } else {
            $safeErrorMessage = $exceptionMessage;
        }
        return view()->make('errors.500')->with('safeErrorMessage', $safeErrorMessage);
    }
}

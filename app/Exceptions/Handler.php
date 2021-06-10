<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
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
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            /*if ($request->expectsJson()) {  //ajax call api method
                return response()->json(['code' => '-1', 'status' => 'auth failed'], 401);
            } else {
                $request_url = $request->path();
                $url_params_arr = explode('/', $request_url);
                //dd($url_params_arr);

                if (in_array('api', $url_params_arr)) {
                    session()->flash('danger', 'Please Login!');

                    return redirect('/api/login');
                }
            }*/

            $request_url = $request->path();
            $url_params_arr = explode('/', $request_url);
            //dd($url_params_arr);

            if (in_array('api', $url_params_arr)) {
                session()->flash('danger', 'Please Login!');

                return redirect('/api/login');
            }
        }

        return parent::render($request, $exception);
    }
}

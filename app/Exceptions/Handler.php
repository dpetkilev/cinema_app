<?php

namespace App\Exceptions;

use Exception, PDOException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
// All database errors
        if($e instanceof PDOException)
        {
            $Resp = array
            (
                'error' => array
                (
                    array
                    (
                        'param' => 'DB',
                        'message' => $e->getMessage()
                    )
                )
            );
            
            return response()->json($Resp);
        }
// Page not found 404
        elseif($e instanceof NotFoundHttpException)
        {
            $Resp = array
            (
                'error' => array
                (
                    array
                    (
                        'param' => 'URL',
                        'message' => 'Unsupported request'
                    )
                )
            );
            
            return response()->json($Resp);
        }
        return parent::render($request, $e);
    }
}

<?php

namespace App\Exceptions;

use App\Transformers\BaseTransformer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ValidationException::class,
    ];

    /**
     * Map of Exceptions that are handled and abstracted
     * 
     * @var array
     */
    protected $exceptionMap = [
        'DBALException'           => 422,
        'ErrorException'          => 400,
        'HttpResponseException'   => 422,
        'NotFoundHttpException'   => 404,
        'EntityNotFoundException' => 404,
        'QueryException '         => 500,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception): Response|JsonResponse
    {
        // return parent::render($request, $exception);
        return $this->handle($exception);
    }

    /**
     * Handles the error.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     */
    private function handle(Throwable $exception): JsonResponse
    {
        $statusCode = 500;
        $message = 'Internal Server Error';
        // Since there are no request paramaters to be validated:
        // - ValidationException will not be caught and will not be removed in the $dontReport variable 
        // - Will also not use the $request from the render method
    
        $exceptionName = class_basename($exception);

        if (array_key_exists($exceptionName, $this->exceptionMap)) {
            $statusCode = $this->exceptionMap[$exceptionName];
            $message = Response::$statusTexts[$statusCode];
        }

        $responseTransformer = new BaseTransformer();
        $response = $responseTransformer->errorResponse([
            $statusCode,
            $message
        ]);

        // Logging the response code can be done here

        return $response;
    }
}

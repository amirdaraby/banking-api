<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * Convert exception to JSON response for API errors
     */
    public function render($request, Throwable $e): JsonResponse|Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    protected function handleApiException($request, Throwable $exception): JsonResponse
    {
        $statusCode = $this->getStatusCode($exception);
        $response = [
            'success' => false,
            'message' => $this->getErrorMessage($exception),
            'code' => $statusCode,
        ];

        if (config('app.debug')) {
            $response['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ];
        }

        return response()->json($response, $statusCode);
    }

    protected function getStatusCode(Throwable $exception): int
    {
        return method_exists($exception, 'getStatusCode')
            ? $exception->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    protected function getErrorMessage(Throwable $exception): string
    {
        return match (true) {
            $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException => __('errors.model_not_found'),
            $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException => __('errors.not_found'),
            $exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException => __('errors.method_not_allowed'),
            $exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException => __('errors.too_many_requests'),
            default => $exception->getMessage() ?: __('errors.server_error'),
        };
    }
}

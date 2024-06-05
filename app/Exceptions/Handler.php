<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        //
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return $this->jsonResponse($e->validator->errors()->toArray(), 400);
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->jsonResponse(__('Not Found'), 404);
        }

        if ($e instanceof AuthenticationException) {
            return $this->jsonResponse(__('Unautenticated'), 401);
        }

        if ($e instanceof AuthorizationException) {
            return $this->jsonResponse(__('Unauthorized'), 403);
        }

        if ($e instanceof TokenMismatchException) {
            return $this->jsonResponse(__('Page Expired'), 419);
        }

        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();

            if ($statusCode === 404) {
                return $this->jsonResponse(__('Not Found'), 404);
            }

            return $this->jsonResponse($e->getMessage() ?? true, $statusCode);
        }

        return parent::render($request, $e);
    }

    protected function jsonResponse($payload, int $statusCode)
    {
        $message = $statusCode === 400 ? collect(array_values((array) $payload))->collapse()->join(' ') : $payload;

        $data = [
            'status_code' => $statusCode,
            'status'      => 'error',
            'message'     => $message,
        ];

        if ($statusCode == 400) {
            $data['errors'] = $payload;
        }

        return response()->json($data, $statusCode);
    }
}

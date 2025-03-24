<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // $this->renderable(function (Throwable $e) {
        //     return $this->handleException($e);
        // });
        $this->renderable(function (Throwable $e, $request) {

            $uriStartsWithApi = strpos($request->getRequestUri(), '/api') === 0;

            if ($uriStartsWithApi) {
                return $this->handleException($e);
            }
            // Handle non-API requests
            // return redirect()->back()->withInput()->withErrors(['message' => $e->getMessage()]);
        });
    }

    public function handleException(Throwable $e)
    {
        if ($e instanceof HttpException) {
            if (array_key_exists('WWW-Authenticate', $e->getHeaders())) {
                if ($e->getHeaders()['WWW-Authenticate'] == 'Basic') {
                }
            } else {
                $code = $e->getStatusCode();
                $defaultMessage = \Symfony\Component\HttpFoundation\Response::$statusTexts[$code];
                $message = $e->getMessage() == "" ? $defaultMessage : $e->getMessage();
                if ($e instanceof NotFoundHttpException) {
                    return $this->errorResponse("Data model yang dicari tidak ditemukan", Response::HTTP_NOT_FOUND);
                }
                return $this->errorResponse($message, $code);
            }
        } else if ($e instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($e->getModel()));
            return $this->errorResponse("Tidak ada data {$model} dengan id yang dimasukan", Response::HTTP_NOT_FOUND);
        } else if ($e instanceof AuthorizationException) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        } else if ($e instanceof AuthenticationException) {
            return $this->errorResponse('Unauthenticated: Token is invalid or has expired, please relogin', Response::HTTP_UNAUTHORIZED);
        } else if ($e instanceof ValidationException) {
            $errors = $e->validator->errors()->getMessages();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else if ($e instanceof FatalError) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            if (config('app.debug')) {
                return $this->dataResponse($e->getMessage(), $e->getCode());
            } else {
                return $this->errorResponse('Terjadi Error', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public static function handleExceptionWeb(Throwable $e)
    {
        if ($e instanceof QueryException) {
            // Handle database query exception
            return back()->withError([
                'error' => "Database error: " . $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        } elseif ($e instanceof ModelNotFoundException) {
            // Handle model not found exception
            $model = strtolower(class_basename($e->getModel()));
            return back()->withError([
                'error' => "Tidak ada data {$model} dengan id yang dimasukan",
                'code' => $e->getCode(),
            ]);
        } elseif ($e instanceof AuthorizationException) {
            // Handle authorization exception
            return back()->withError([
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        } elseif ($e instanceof HttpException) {
            // Handle HTTP exception
            $code = $e->getStatusCode();
            $defaultMessage = \Symfony\Component\HttpFoundation\Response::$statusTexts[$code];
            $message = $e->getMessage() == "" ? $defaultMessage : $e->getMessage();
            return back()->withError([
                'error' => $message,
                'code' => $e->getCode(),
            ]);
        } elseif ($e instanceof FileNotFoundException) {
            // Handle file not found exception
            return back()->withError([
                'error' => "The requested file was not found.",
                'code' => Response::HTTP_NOT_FOUND,
            ]);
        } elseif ($e instanceof NotFoundHttpException) {
            // Handle file not found exception
            return back()->withError([
                'error' => "Data model yang dicari tidak ditemukan",
                'code' => Response::HTTP_NOT_FOUND
            ]);
        } else {
            // Handle other unexpected exceptions
            return back()->withError([
                'error' => "Unexpected error: " . $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        }
    }

    public static function handleExceptionValidationWeb(Throwable $e)
    {
        if ($e instanceof ValidationException) {
            // Handle validation exception
            $errors = $e->validator->errors()->toArray();
            return back()->withErrors([
                    'error' => $errors,
                    'code' => $e->getCode(),
            ])->withInput();
        }
    }

    /**
     * Data Response
     * @param $data
     * @return JsonResponse
     */
    public function dataResponse($data, $code): JsonResponse
    {
        if ($code === 401) {
            return $this->errorResponse('Unauthorized: Token tidak ditemukan / sudah kadaluarsa', 401);
        }
        return response()->json(['error' => $data, 'code' => $code], Response::HTTP_OK);
    }

    /**
     * Success Response
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse(string $message, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['success' => $message, 'code' => $code], $code);
    }

    /**
     * Error Response
     * @param $message
     * @param int $code
     * @return JsonResponse
     *
     */
    public function errorResponse($message, $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }
}

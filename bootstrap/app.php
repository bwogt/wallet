<?php

use App\Http\Messages\FlashMessage;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(fn ($request) => $request->is('api/*'));

        $exceptions->render(function (HttpException $e) {
            $status = $e->getStatusCode();

            return match ($status) {
                Response::HTTP_UNAUTHORIZED => response()->json(
                    FlashMessage::error(trans('http_exceptions.unauthenticated')),
                    $status
                ),
                Response::HTTP_FORBIDDEN => response()->json(
                    FlashMessage::error(trans('http_exceptions.unauthorized')),
                    $status
                ),
                Response::HTTP_NOT_FOUND => response()->json(
                    FlashMessage::error(trans('http_exceptions.not_found')),
                    $status
                ),
                Response::HTTP_TOO_MANY_REQUESTS => response()->json(
                    FlashMessage::error(trans('http_exceptions.to_many_requests')),
                    $status
                ),
                default => null,
            };

        });

        $exceptions->render(function (Throwable $e, $request) {
            Log::error($e);

            if (($request->is('api/*') || $request->wantsJson())) {
                return response()->json(
                    FlashMessage::error(trans('http_exceptions.internal_server')),
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }

            return null;
        });
    })->create();

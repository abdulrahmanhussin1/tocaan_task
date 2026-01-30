<?php

namespace App\Exceptions;

use App\Enums\AppEnvironmentEnum;
use App\Exceptions\Factories\ExceptionHandlerFactory;
use App\Exceptions\Handlers\UnhandledExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [

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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     * @param Request $request
     * @param Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response
    {
        if ($handler = ExceptionHandlerFactory::getExceptionHandler($e)) {
            return call_user_func($handler . '::handle', $request, $e);
        }

        if (App::environment(AppEnvironmentEnum::LOCAL->value)) {
            return parent::render($request, $e);
        }

        return UnhandledExceptionHandler::handle($request, $e);
    }
}

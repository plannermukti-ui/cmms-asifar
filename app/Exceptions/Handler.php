<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

        $this->renderable(function (\Throwable $e, $request) {
            if ($e instanceof \Spatie\Permission\Exceptions\UnauthorizedException || 
                $e instanceof \Illuminate\Auth\Access\AuthorizationException || 
                ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException)) {
                
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Akses Ditolak: Anda tidak memiliki hak akses.'], 403);
                }
                
                return redirect($request->headers->get('referer', route('dashboard')))->with('error', 'Akses Ditolak: Anda tidak memiliki hak akses (permission) untuk membuka halaman tersebut atau melakukan tindakan ini.');
            }
        });
    }
}

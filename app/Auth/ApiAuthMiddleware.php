<?php namespace App\Auth;

use Closure;

class ApiAuthMiddleware
{
    protected $auth;

    public function __construct(StatelessGuard $guard)
    {
        $this->auth = $guard;
    }

    public function handle($request, Closure $next)
    {
        if (is_null($this->auth->user())) {
            return response([
                'message' => 'This route is protected, you must be authenticated.',
                'error' => 'No bearer or invalid token.'
            ], 403);
        }

        return $next($request);
    }
}
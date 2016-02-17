<?php namespace App\Auth;

use Closure;

class ApiAuthMiddleware
{
    protected $auth;

    /**
     * ApiAuthMiddleware constructor.  Injects the StatelessGaurd provider into the middleware to check for the status
     * of authentication on the current request.
     * @param StatelessGuard $guard
     */
    public function __construct(StatelessGuard $guard)
    {
        $this->auth = $guard;
    }

    /**
     * Attempt to authenticate the user of the current request by extracting the bearer token from the headers and
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
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
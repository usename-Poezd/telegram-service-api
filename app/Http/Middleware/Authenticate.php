<?php

namespace App\Http\Middleware;

use Closure;
use danog\MadelineProto\API;
class Authenticate
{
    protected API $telegram_api;

    /**
     * Create a new middleware instance.
     *
     * @param API $auth
     */
    public function __construct(API $telegram_api)
    {
        $this->telegram_api = $telegram_api;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->telegram_api->getAuthorization()) {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }
}

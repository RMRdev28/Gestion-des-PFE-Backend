<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $jwt = $request->cookie('jwt');
        if($jwt){
            $request->headers->set('Authorization','Bearer '.$jwt);
        }
        $this->authenticate($request, $guards);

        return $next($request);
    }
}

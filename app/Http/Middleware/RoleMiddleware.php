<?php

namespace App\Http\Middleware;

use Closure;
use http\Client\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param $role_name
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request , Closure $next, $role_name)
    {
        $result = $request->user()->hasRole($role_name);
        if(!$result)
            return response('Role needed.', 422);

        return $next($request);
    }

}

<?php

namespace App\Http\Middleware;

use Closure;

class UserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $requiredType The user type required for this page. Accepts a pipe-delimited list.
     * @return mixed
     */
    public function handle($request, Closure $next, $requiredType)
    {
        $types = explode("|", $requiredType);
        $found = false;
        $user = $request->user();
        if ($user) {
            foreach ($types as $type) {
                $found = $found || $request->user()->isType($type);
            }
        }

        if (!$found) {
            abort(403);
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictAccessByCountry
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}"));
        
        if ($details->country == 'IN') {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}

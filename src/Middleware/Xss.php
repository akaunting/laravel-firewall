<?php

namespace Akaunting\Firewall\Middleware;

use Closure;

class Xss extends Base
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->skip($request)) {
            return $next($request);
        }

        $patterns = [
            '#<[^>]*\w*\"?[^>]*>#is',
        ];

        if ($this->check($patterns)) {
            return $this->respond(config('firewall.responses.block'));
        }

        return $next($request);
    }
}

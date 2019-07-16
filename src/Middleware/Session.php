<?php

namespace Akaunting\Firewall\Middleware;

use Closure;

class Session extends Base
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
            '@[\|:]O:\d{1,}:"[\w_][\w\d_]{0,}":\d{1,}:{@i',
            '@[\|:]a:\d{1,}:{@i',
        ];

        if ($this->check($patterns)) {
            return $this->respond(config('firewall.responses.block'));
        }

        return $next($request);
    }
}

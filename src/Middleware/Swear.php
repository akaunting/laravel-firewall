<?php

namespace Akaunting\Firewall\Middleware;

use Closure;

class Swear extends Base
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

        if (!$words = config('firewall.middleware.' . $this->middleware . '.words')) {
            return $next($request);
        }

        foreach ((array) $words as $word) {
            $patterns = [
                '#\b' . $word . '\b#i',
            ];

            if ($this->check($patterns)) {
                return $this->respond(config('firewall.responses.block'));
            }
        }

        return $next($request);
    }
}

<?php

namespace Akaunting\Firewall\Middleware;

use Closure;

class Url extends Base
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

        if ($this->check([])) {
            return $this->respond(config('firewall.responses.block'));
        }

        return $next($request);
    }
    
    public function check($patterns)
    {
        return in_array($this->request->url(), config('firewall.' . $this->middleware . '.inspections'));
    }
}

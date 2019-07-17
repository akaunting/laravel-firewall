<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Events\AttackDetected;
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
        $protected = in_array($this->request->url(), config('firewall.middleware.' . $this->middleware . '.inspections'));

        if ($protected) {
            $log = $this->log();

            event(new AttackDetected($log));
        }

        return $protected;
    }
}

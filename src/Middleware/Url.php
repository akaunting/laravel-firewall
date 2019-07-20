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
        $protected = false;

        if (!$inspections = config('firewall.middleware.' . $this->middleware . '.inspections')) {
            return $protected;
        }

        foreach ($inspections as $inspection) {
            if (!$this->request->is($inspection)) {
                continue;
            }

            $protected = true;

            break;
        }

        if ($protected) {
            $log = $this->log();

            event(new AttackDetected($log));
        }

        return $protected;
    }
}

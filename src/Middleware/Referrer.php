<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Events\AttackDetected;
use Closure;

class Referrer extends Base
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
        $status = false;

        if (!$blocked = config('firewall.middleware.' . $this->middleware . '.blocked')) {
            return $status;
        }

        if (in_array((string) $this->request->server('HTTP_REFERER'), (array) $blocked)) {
            $status = true;
        }

        if ($status) {
            $log = $this->log();

            event(new AttackDetected($log));
        }

        return $status;
    }
}

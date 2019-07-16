<?php

namespace Akaunting\Firewall\Middleware;

use Closure;

class Sqli extends Base
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
            '#[\d\W](union select|union join|union distinct)[\d\W]#is',
            '#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is',
        ];

        if ($this->check($patterns)) {
            return $this->respond(config('firewall.responses.block'));
        }

        return $next($request);
    }
}

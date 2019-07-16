<?php

namespace Akaunting\Firewall\Middleware;

use Closure;

class Php extends Base
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
            'bzip2://',
            'expect://',
            'glob://',
            'phar://',
            'php://',
            'ogg://',
            'rar://',
            'ssh2://',
            'zip://',
            'zlib://',
        ];

        if ($this->check($patterns)) {
            return $this->respond(config('firewall.responses.block'));
        }

        return $next($request);
    }
    
    public function match($pattern, $input)
    {
        $result = false;

        if (!is_array($input) && !is_string($input)) {
            return false;
        }

        if (!is_array($input)) {
            return (stripos($input, $pattern) === 0);
        }

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                if (!$result = $this->match($pattern, $value)) {
                    continue;
                }

                break;
            }

            if (!$result = (stripos($value, $pattern) === 0)) {
                continue;
            }

            break;
        }

        return $result;
    }
}

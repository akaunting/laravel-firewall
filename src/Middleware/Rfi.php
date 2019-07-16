<?php

namespace Akaunting\Firewall\Middleware;

use Closure;

class Rfi extends Base
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
            '#(http|ftp){1,1}(s){0,1}://.*#i',
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
            if (!$result = preg_match($pattern, $this->applyExceptions($input))) {
                return false;
            }

            return $this->checkContent($result);
        }

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                if (!$result = $this->match($pattern, $value)) {
                    continue;
                }

                break;
            }

            if (!$result = preg_match($pattern, $this->applyExceptions($value))) {
                continue;
            }

            if (!$this->checkContent($result)) {
                continue;
            }

            break;
        }

        return $result;
    }
    
    protected function applyExceptions($string)
    {
        $exceptions = config('firewall.' . $this->middleware . '.exceptions');

        $domain = $this->request->getHost();

        $exceptions[] = 'http://' . $domain;
        $exceptions[] = 'https://' . $domain;
        $exceptions[] = 'http://&';
        $exceptions[] = 'https://&';

        return str_replace($exceptions, '', $string);
    }
    
    protected function checkContent($value)
    {
        $contents = @file_get_contents($value);

        if (!empty($contents)) {
            return (strstr($contents, '<?php') !== false);
        }
        
        return false;
    }
}

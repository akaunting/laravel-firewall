<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Events\AttackDetected;
use Akaunting\Firewall\Models\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

abstract class Base
{
    public $request = null;
    public $input = null;
    public $middleware = null;
    public $user_id = null;

    public function skip($request)
    {
        $this->prepare($request);
        
        if (!$this->enabled()) {
            return true;
        }
        
        if ($this->whitelist()) {
            return true;
        }
        
        if (!$this->method()) {
            return true;
        }

        if ($this->url()) {
            return true;
        }
    
        return false;
    }
    
    public function prepare($request)
    {
        $this->request = $request;
        $this->input = $request->input();
        $this->middleware = strtolower((new \ReflectionClass($this))->getShortName());
        $this->user_id = auth()->id() ?: 0;
    }
    
    public function enabled()
    {
        return config('firewall.enabled');
    }
    
    public function whitelist()
    {
        return in_array($this->ip(), config('firewall.whitelist'));
    }
    
    public function method()
    {
        $requests = config('firewall.middleware.' . $this->middleware . '.requests');

        if (in_array('all', $requests)) {
            return true;
        }
    
        return in_array(strtolower($this->request->method()), $requests);
    }

    public function url()
    {
        if (!$urls = config('firewall.middleware.' . $this->middleware . '.urls')) {
            return false;
        }

        foreach ($urls['except'] as $ex) {
            if (!$this->request->is($ex)) {
                continue;
            }

            return true;
        }

        foreach ($urls['only'] as $on) {
            if ($this->request->is($on)) {
                continue;
            }

            return true;
        }

        return false;
    }
    
    public function ip()
    {
        if ($cf_ip = $this->request->header('CF_CONNECTING_IP')) {
            $ip = $cf_ip;
        } else {
            $ip = $this->request->ip();
        }
        
        return $ip;
    }
    
    public function check($patterns)
    {
        $log = null;

        foreach ($patterns as $pattern) {
            if (!$match = $this->match($pattern, $this->input)) {
                continue;
            }

            $log = $this->log();

            event(new AttackDetected($log));

            break;
        }

        if ($log) {
            return true;
        }
        
        return false;
    }
    
    public function match($pattern, $input)
    {
        $result = false;

        if (!is_array($input) && !is_string($input)) {
            return false;
        }

        if (!is_array($input)) {
            return preg_match($pattern, $input);
        }

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                if (!$result = $this->match($pattern, $value)) {
                    continue;
                }

                break;
            }

            if (!$result = preg_match($pattern, $value)) {
                continue;
            }

            break;
        }

        return $result;
    }
    
    public function log()
    {
        $log = Log::create([
            'ip' => $this->ip(),
            'level' => 'medium',
            'middleware' => $this->middleware,
            'user_id' => $this->user_id,
            'url' => $this->request->fullUrl(),
            'referrer' => $this->request->server('HTTP_REFERER') ?: 'NULL',
            'request' => urldecode(http_build_query($this->input)),
        ]);

        return $log;
    }
    
    public function respond($response, $data = [])
    {
        if ($response['code'] == 200) {
            return '';
        }

        if ($view = $response['view']) {
            return Response::view($view, $data);
        }
        
        if ($redirect = $response['redirect']) {
            if (($this->middleware == 'ip') && $this->request->is($redirect)) {
                abort($response['code'], $response['message']);
            }

            return Redirect::to($redirect);
        }

        if ($response['abort']) {
            abort($response['code'], $response['message']);
        }
        
        return Response::make($response['message'], $response['code']);
    }
}

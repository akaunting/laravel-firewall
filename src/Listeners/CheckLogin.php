<?php

namespace Akaunting\Firewall\Listeners;

use Akaunting\Firewall\Events\AttackDetected;
use Akaunting\Firewall\Models\Log;
use Illuminate\Auth\Events\Failed as Event;

class CheckLogin
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skip($event)) {
            return;
        }

        $log = $this->log();

        event(new AttackDetected($log));
    }

    public function skip($event)
    {
        $this->request = request();

        if (!config('firewall.enabled') || !config('firewall.middleware.login.enabled')) {
            return true;
        }

        if (in_array($this->ip(), config('firewall.whitelist'))) {
            return true;
        }
    }

    public function log()
    {
        return Log::create([
            'ip' => $this->ip(),
            'level' => 'medium',
            'middleware' => 'login',
            'user_id' => '0',
            'url' => $this->request->fullUrl(),
            'referrer' => $this->request->server('HTTP_REFERER') ?: 'NULL',
            'request' => urldecode(http_build_query($this->request->input())),
        ]);
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
}

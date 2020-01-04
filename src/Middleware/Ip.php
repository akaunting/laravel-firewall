<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Abstracts\Middleware;
use Akaunting\Firewall\Models\Ip as Model;
use Illuminate\Support\Facades\Schema;

class Ip extends Middleware
{
    public function check($patterns)
    {
        if (!Schema::hasTable('firewall_ips')) {
            return false;
        }

        return Model::blocked($this->ip())->pluck('id')->first();
    }
}

<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Abstracts\Middleware;
use Akaunting\Firewall\Models\Ip as Model;

class Ip extends Middleware
{
    public function check($patterns)
    {
        return Model::blocked($this->ip())->pluck('id')->first();
    }
}

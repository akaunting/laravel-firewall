<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Models\Ip as Model;

class Ip extends Base
{
    public function check($patterns)
    {
        return Model::blocked($this->ip())->pluck('id')->first();
    }
}

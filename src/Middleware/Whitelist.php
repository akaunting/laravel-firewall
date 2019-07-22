<?php

namespace Akaunting\Firewall\Middleware;

class Whitelist extends Base
{
    public function check($patterns)
    {
        return ($this->whitelist() === false);
    }
}

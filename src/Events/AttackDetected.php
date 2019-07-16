<?php

namespace Akaunting\Firewall\Events;

use Illuminate\Contracts\Queue\ShouldQueue;

class AttackDetected implements ShouldQueue
{
    public $log;

    /**
     * Create a new event instance.
     */
    public function __construct($log)
    {
        $this->log = $log;
    }
}

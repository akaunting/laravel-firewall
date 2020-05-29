<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Abstracts\Middleware;
use Akaunting\Firewall\Models\Tarpit as model;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class Tarpit extends Middleware
{
    public function check($patterns)
    {
        $violationFound = false;

        $blockedUntil = model::blockedUntil($this->ip());
        if ($blockedUntil) {
            $violationFound = true;
            $tryAganiInSeconds = Carbon::now()->diffInSeconds($blockedUntil);

            $waitTime =  floor($tryAganiInSeconds/ 60). ":". ($tryAganiInSeconds % 60);

            $this->data = [
                'try_again_in_mintues' => $waitTime
            ];
        }

        return $violationFound;
    }
}

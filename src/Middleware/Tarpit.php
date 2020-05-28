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
            $tryAganiInMinutes = Carbon::now()->diffInMinutes($blockedUntil);

            $this->data = [
                'try_again_in_mintues' => $tryAganiInMinutes
            ];
        }

        return $violationFound;
    }
}

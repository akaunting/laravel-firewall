<?php

namespace Akaunting\Firewall\Middleware;

class Php extends Base
{
    public function match($pattern, $input)
    {
        $result = false;

        if (!is_array($input) && !is_string($input)) {
            return false;
        }

        if (!is_array($input)) {
            return (stripos($input, $pattern) === 0);
        }

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                if (!$result = $this->match($pattern, $value)) {
                    continue;
                }

                break;
            }

            if (!$result = (stripos($value, $pattern) === 0)) {
                continue;
            }

            break;
        }

        return $result;
    }
}

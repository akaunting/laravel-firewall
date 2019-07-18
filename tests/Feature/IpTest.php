<?php

namespace Akaunting\Firewall\Tests\Feature;

use Akaunting\Firewall\Middleware\Ip;
use Akaunting\Firewall\Models\Ip as Model;
use Akaunting\Firewall\Tests\TestCase;

class IpTest extends TestCase
{
    public function testShouldPass()
    {
        $this->assertEquals('next', (new Ip())->handle($this->request, $this->getNextClosure()));
    }

    public function testShouldFail()
    {
        Model::create(['ip' => '127.0.0.1', 'log_id' => 1]);

        $this->assertNotEquals('next', (new Ip())->handle($this->request, $this->getNextClosure()));
    }
}

<?php

namespace Akaunting\Firewall\Tests\Feature;

use Akaunting\Firewall\Middleware\Lfi;
use Akaunting\Firewall\Tests\TestCase;

class LfiTest extends TestCase
{
    public function testShouldPass()
    {
        $this->assertEquals('next', (new Lfi())->handle($this->request, $this->getNextClosure()));
    }

    public function testShouldFail()
    {
        $this->request->query->set('foo', '../../../../etc/passwd');

        $this->assertEquals('403', (new Lfi())->handle($this->request, $this->getNextClosure())->getStatusCode());
    }
}

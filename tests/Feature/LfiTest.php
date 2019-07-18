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
        $this->request->request->set('foo', '../../../../etc/passwd');

        $this->assertNotEquals('next', (new Lfi())->handle($this->request, $this->getNextClosure()));
    }
}

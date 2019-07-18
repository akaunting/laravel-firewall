<?php

namespace Akaunting\Firewall\Tests\Feature;

use Akaunting\Firewall\Middleware\Sqli;
use Akaunting\Firewall\Tests\TestCase;

class SqliTest extends TestCase
{
    public function testShouldPass()
    {
        $this->assertEquals('next', (new Sqli())->handle($this->request, $this->getNextClosure()));
    }

    public function testShouldFail()
    {
        $this->request->query->set('foo', '-1+union+select+1,2,3,4,5,6,7,8,9,(SELECT+password+FROM+users+WHERE+ID=1)');

        $this->assertEquals('403', (new Sqli())->handle($this->request, $this->getNextClosure())->getStatusCode());
    }
}

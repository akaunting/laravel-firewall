<?php

namespace Akaunting\Firewall\Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $request;

    protected function setUp()
    {
        parent::setUp();

        $this->request = request();
    }

    public function getNextClosure()
    {
        return function () {
            return 'next';
        };
    }
}

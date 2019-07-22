<?php

namespace Akaunting\Firewall\Tests;

use Akaunting\Firewall\Provider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $database;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->setUpConfig();

        $this->artisan('vendor:publish', ['--tag' => 'firewall']);
        $this->artisan('migrate:refresh', ['--database' => 'testbench']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->deleteDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            Provider::class,
        ];
    }

    protected function setUpConfig()
    {
        config(['firewall' => require __DIR__ . '/../src/Config/firewall.php']);

        config(['firewall.notifications.mail.enabled' => false]);
        config(['firewall.middleware.ip.methods' => ['all']]);
        config(['firewall.middleware.lfi.methods' => ['all']]);
        config(['firewall.middleware.rfi.methods' => ['all']]);
        config(['firewall.middleware.sqli.methods' => ['all']]);
        config(['firewall.middleware.xss.methods' => ['all']]);
    }

    protected function setUpDatabase()
    {
        if (!file_exists($path = __DIR__ . '/databases')) {
            mkdir($path);
        }

        touch($this->database = tempnam($path, 'database.sqlite.'));

        app()->config->set(
            'database.connections.testbench', [
                'driver'   => 'sqlite',
                'database' => $this->database,
                'prefix'   => '',
            ]
        );
    }

    protected function deleteDatabase()
    {
        @unlink($this->database);
    }

    public function getNextClosure()
    {
        return function () {
            return 'next';
        };
    }
}

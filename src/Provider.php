<?php

namespace Akaunting\Firewall;

use Akaunting\Firewall\Events\AttackDetected;
use Akaunting\Firewall\Listeners\BlockIp;
use Akaunting\Firewall\Listeners\NotifyUsers;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $this->publishes([
            __DIR__ . '/Config/firewall.php'                                            => config_path('firewall.php'),
            __DIR__ . '/Migrations/2019_07_15_000000_create_firewall_ips_table.php'     => database_path('migrations/2019_07_15_000000_create_firewall_ips_table.php'),
            __DIR__ . '/Migrations/2019_07_15_000000_create_firewall_logs_table.php'    => database_path('migrations/2019_07_15_000000_create_firewall_logs_table.php'),
        ], 'firewall');

        $router->middlewareGroup('firewall.all', config('firewall.all_middlewares'));
        $router->aliasMiddleware('firewall.ip', 'Akaunting\Firewall\Middleware\Ip');
        $router->aliasMiddleware('firewall.lfi', 'Akaunting\Firewall\Middleware\Lfi');
        $router->aliasMiddleware('firewall.php', 'Akaunting\Firewall\Middleware\Php');
        $router->aliasMiddleware('firewall.rfi', 'Akaunting\Firewall\Middleware\Rfi');
        $router->aliasMiddleware('firewall.session', 'Akaunting\Firewall\Middleware\Session');
        $router->aliasMiddleware('firewall.sqli', 'Akaunting\Firewall\Middleware\Sqli');
        $router->aliasMiddleware('firewall.whitelist', 'Akaunting\Firewall\Middleware\Whitelist');
        $router->aliasMiddleware('firewall.xss', 'Akaunting\Firewall\Middleware\Xss');

        $this->app['events']->listen(AttackDetected::class, BlockIp::class);
        $this->app['events']->listen(AttackDetected::class, NotifyUsers::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/firewall.php', 'firewall');
    }
}

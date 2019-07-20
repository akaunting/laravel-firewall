<?php

namespace Akaunting\Firewall;

use Akaunting\Firewall\Events\AttackDetected;
use Akaunting\Firewall\Listeners\BlockIp;
use Akaunting\Firewall\Listeners\CheckLogin;
use Akaunting\Firewall\Listeners\NotifyUsers;
use Illuminate\Auth\Events\Failed as LoginFailed;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->publishes([
            __DIR__ . '/Config/firewall.php'                                            => config_path('firewall.php'),
            __DIR__ . '/Migrations/2019_07_15_000000_create_firewall_ips_table.php'     => database_path('migrations/2019_07_15_000000_create_firewall_ips_table.php'),
            __DIR__ . '/Migrations/2019_07_15_000000_create_firewall_logs_table.php'    => database_path('migrations/2019_07_15_000000_create_firewall_logs_table.php'),
            __DIR__ . '/Resources/lang'                                                 => resource_path('lang/vendor/firewall'),
        ], 'firewall');

        $this->registerMiddleware($router);
        $this->registerListeners();
        $this->registerTranslations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/firewall.php', 'firewall');

        $this->app->register(\Jenssegers\Agent\AgentServiceProvider::class);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $lang_path = resource_path('lang/vendor/firewall');

        if (is_dir($lang_path)) {
            $this->loadTranslationsFrom($lang_path, 'firewall');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/Resources/lang', 'firewall');
        }
    }

    /**
     * Register middleware.
     *
     * @param Router $router
     *
     * @return void
     */
    public function registerMiddleware($router)
    {
        $router->middlewareGroup('firewall.all', config('firewall.all_middleware'));
        $router->aliasMiddleware('firewall.agent', 'Akaunting\Firewall\Middleware\Agent');
        $router->aliasMiddleware('firewall.ip', 'Akaunting\Firewall\Middleware\Ip');
        $router->aliasMiddleware('firewall.lfi', 'Akaunting\Firewall\Middleware\Lfi');
        $router->aliasMiddleware('firewall.php', 'Akaunting\Firewall\Middleware\Php');
        $router->aliasMiddleware('firewall.referrer', 'Akaunting\Firewall\Middleware\Referrer');
        $router->aliasMiddleware('firewall.rfi', 'Akaunting\Firewall\Middleware\Rfi');
        $router->aliasMiddleware('firewall.session', 'Akaunting\Firewall\Middleware\Session');
        $router->aliasMiddleware('firewall.sqli', 'Akaunting\Firewall\Middleware\Sqli');
        $router->aliasMiddleware('firewall.swear', 'Akaunting\Firewall\Middleware\Swear');
        $router->aliasMiddleware('firewall.url', 'Akaunting\Firewall\Middleware\Url');
        $router->aliasMiddleware('firewall.whitelist', 'Akaunting\Firewall\Middleware\Whitelist');
        $router->aliasMiddleware('firewall.xss', 'Akaunting\Firewall\Middleware\Xss');
    }

    /**
     * Register listeners.
     *
     * @return void
     */
    public function registerListeners()
    {
        $this->app['events']->listen(AttackDetected::class, BlockIp::class);
        $this->app['events']->listen(AttackDetected::class, NotifyUsers::class);
        $this->app['events']->listen(LoginFailed::class, CheckLogin::class);
    }
}

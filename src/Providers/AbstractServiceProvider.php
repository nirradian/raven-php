<?php

namespace Twine\Raven\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RavenHandler;
use Twine\Raven\Client;

abstract class AbstractServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app[Client::class] = $this->app->share(function ($app) {
            return new Client($app['config']['raven'], $app['queue'], $app->environment());
        });
    }

    /**
     * Get path to config.
     *
     * @return string 
     */
    protected function getPath()
    {
        return realpath(__DIR__.'/../config/config.php');
    }

    /**
     * Get Raven monolog handler.
     *
     * @return RavenHandler
     */
    protected function getHandler()
    {
        $handler = new RavenHandler($this->app[Client::class], $this->app['config']['raven.level']);
        $handler->setFormatter(new LineFormatter("%message% %context% %extra%\n"));

        return $handler;
    }
}

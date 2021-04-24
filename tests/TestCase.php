<?php

namespace alirezap30web\ShortUrl\Tests;

abstract class TestCase extends  \Orchestra\Testbench\TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'shorturl');
        $app['config']->set('database.connections.shorturl', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return ['alirezap30web\ShortUrl\ShorturlServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Shorturl' => 'alirezap30web\ShortUrl\Facades\Shorturl'
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/../migrations/');
    }
}

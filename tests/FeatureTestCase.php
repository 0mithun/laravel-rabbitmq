<?php

namespace Mithun\LaravelRabbitMQ\Tests;

use Mithun\LaravelRabbitMQ\LaravelRabbitQueueServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class FeatureTestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelRabbitQueueServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $config = $this->loadConfig();

        //Config::set($config);
        config()->set('queue.connections.rabbitmq', $config);
    }

    private function loadConfig(): array
    {
        return require(__DIR__ . '/../config/RabbitMQConnectionConfig.php');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}

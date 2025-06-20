<?php

namespace Mithun\LaravelRabbitMQ;

use Mithun\LaravelRabbitMQ\Connectors\RabbitMQConnector;
use Mithun\LaravelRabbitMQ\Console\ConsumeCommand;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

final class LaravelRabbitQueueServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/RabbitMQConnectionConfig.php',
            'queue.connections.rabbitmq'
        );

        if ($this->app->runningInConsole()) {
            $this->app->singleton('rabbitmq.consumer', function ($app): Consumer {
                $isDownForMaintenance = fn (): bool => $app->isDownForMaintenance();

                return new Consumer(
                    $app['queue'],
                    $app['events'],
                    $app[ExceptionHandler::class],
                    $isDownForMaintenance
                );
            });

            $this->app->singleton(ConsumeCommand::class, static function ($app): ConsumeCommand {
                return new ConsumeCommand(
                    $app['rabbitmq.consumer'],
                    $app['cache.store']
                );
            });

            $this->commands([
                ConsumeCommand::class,
            ]);
        }
    }

    public function boot(): void
    {
        $this->app['queue']->addConnector('rabbitmq', function () {
            return new RabbitMQConnector($this->app['events']);
        });
    }
}

# RabbitMQ Queue driver for Laravel


[![Latest Stable Version](https://poser.pugx.org/0mithun/laravel-rabbitmq/v/stable?format=flat-square)](https://packagist.org/packages/0mithun/laravel-rabbitmq)
[![Total Downloads](https://poser.pugx.org/0mithun/laravel-rabbitmq/downloads?format=flat-square)](https://packagist.org/packages/0mithun/laravel-rabbitmq)
[![License](https://poser.pugx.org/vladimir-yuldashev/laravel-queue-rabbitmq/license?format=flat-square)](https://packagist.org/packages/0mithun/laravel-rabbitmq)
[![Tests](https://github.com/0mithun/LaravelRabbitMQ/actions/workflows/tests.yml/badge.svg)](https://github.com/0mithun/LaravelRabbitMQ/actions/workflows/tests.yml)
[![Code style](https://github.com/0mithun/LaravelRabbitMQ/actions/workflows/code-style.yml/badge.svg)](https://github.com/0mithun/LaravelRabbitMQ/actions/workflows/code-style.yml)

The connection abstracts the socket connection, and takes care of protocol version negotiation and authentication and so
on for us. Here we connect to a RabbitMQ node on the local machine - hence the localhost. If we wanted to connect to a
node on a different machine or to a host hosting a [proxy recommended for PHP clients](https://github.com/cloudamqp/amqproxy), we'd simply specify its hostname
or IP address here.

## Support Policy

Only the latest version will get new features. Bug fixes will be provided using the following scheme:

| Package Version | Laravel Version | PHP Version | Bug Fixes Until   |                                                                                     |
|-----------------|-----------------|-------------|-------------------|-------------------------------------------------------------------------------------|
| 0.1               | 10, 11           | ^8.2        | August 26th, 2025 | [Documentation](https://github.com/0mithun/LaravelRabbitMQ/blob/master/README.md) |

this is experimental version of the package

## Installation

You can install this package via composer using this command:

```
composer require 0mithun/laravel-rabbitmq
```

The package will automatically register itself.

Add connection to config/queue.php:

```php
    'connections' => [
        // .....
        'rabbitmq' => [
            'driver' => 'rabbitmq',
            'queue'  => env('RABBITMQ_QUEUE', 'default'),

            'hosts' => [
                'host'      => env('RABBITMQ_HOST', '127.0.0.1'),
                'port'      => env('RABBITMQ_PORT', 5672),
                'user'      => env('RABBITMQ_USER', 'guest'),
                'password'  => env('RABBITMQ_PASSWORD', 'guest'),
                'vhost'     => env('RABBITMQ_VHOST', '/'),
                'lazy'      => env('RABBITMQ_LAZY_CONNECTION', true),
                'keepalive' => env('RABBITMQ_KEEPALIVE_CONNECTION', false),
                'heartbeat' => env('RABBITMQ_HEARTBEAT_CONNECTION', 0),
                'secure'    => env('RABBITMQ_SECURE', false),
            ],

            'options' => [
                'ssl_options' => [
                    'cafile'      => env('RABBITMQ_SSL_CAFILE', null),
                    'local_cert'  => env('RABBITMQ_SSL_LOCALCERT', null),
                    'local_key'   => env('RABBITMQ_SSL_LOCALKEY', null),
                    'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
                    'passphrase'  => env('RABBITMQ_SSL_PASSPHRASE', null),
                ],
                'queue'       => [
                    'job' => \Mithun\LaravelRabbitMQ\Jobs\RabbitMQJob::class,
                    'qos' => [
                        'prefetch_size' => 0,
                        'prefetch_count' => 10,
                        'global' => false
                    ]
                ],
            ],
        ]
    ]
```

## Laravel Usage

Once you completed the configuration you can use Laravel Queue API. If you used other queue drivers you do not need to
change anything else. If you do not know how to use Queue API, please refer to the official Laravel
documentation: http://laravel.com/docs/queues

## Lumen Usage

For Lumen usage the service provider should be registered manually as follows in `bootstrap/app.php`:

```php
$app->register(Mithun\LaravelRabbitMQ\LaravelRabbitQueueServiceProvider::class);
```

## Consuming Messages

There are two ways of consuming messages.

1. `queue:work` command which is Laravel's built-in command. This command utilizes `basic_get`.

2. `rabbitmq:consume` command which is provided by this package. This command utilizes `basic_consume` and is more
   performant than `basic_get` by ~3x.

```shell
  php artisan rabbitmq:consume --queue=customQueue
```

You can create jobs with custom queue same as below

```php
class TestJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this->onQueue('customQueue');
    }

    public function handle()
    {
        return true;
    }
}

```

Queues and Exchanges will be created automatically
# laravel-rabbitmq

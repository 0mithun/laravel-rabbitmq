<?php

namespace Mithun\LaravelRabbitMQ\Tests\Feature\Connector;

use Mithun\LaravelRabbitMQ\RabbitQueue;
use Mithun\LaravelRabbitMQ\Tests\FeatureTestCase;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnectorTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testRabbitMQQueueIsLazyConnection(): void
    {
        $getQueueInstance = $this->app['queue'];

        //        $this->app['config']->set('queue.connections.rabbitmq.hosts.lazy', true);

        $connection = $getQueueInstance->connection('rabbitmq');

        $this->assertInstanceOf(RabbitQueue::class, $connection);
        $this->assertInstanceOf(AMQPStreamConnection::class, $connection->getConnection());
        $this->assertTrue($connection->getConnection()->isConnected());
        $this->assertTrue($connection->getConnection()->channel()->is_open());
    }

    public function testRabbitMQQueueIsNotLazyConnection(): void
    {
        config()->set('queue.connections.rabbitmq.hosts.lazy', false);
        $getQueueInstance = $this->app['queue'];

        $connection = $getQueueInstance->connection('rabbitmq');

        $this->assertInstanceOf(RabbitQueue::class, $connection);
        $this->assertInstanceOf(AMQPStreamConnection::class, $connection->getConnection());
        $this->assertTrue($connection->getConnection()->isConnected());
        $this->assertTrue($connection->getConnection()->channel()->is_open());
    }
}

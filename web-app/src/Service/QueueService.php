<?php

namespace App\Service;

use App\Api\ImportUsersCSV\Handler;
use Lib\App\SerializerInterface;
use Lib\Queue\QueueMessageInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueService
{
    private const DEFAULT_QUEUE_NAME = 'import';

    /**
     * @var AMQPStreamConnection
     */
    private AMQPStreamConnection $connection;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;
    /**
     * @var Handler
     */

    /**
     * QueueService constructor.
     * @param AMQPStreamConnection $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(AMQPStreamConnection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param QueueMessageInterface $msg
     * @param string $queueName
     */
    public function send(QueueMessageInterface $msg, string $queueName = self::DEFAULT_QUEUE_NAME)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, false, false, false);

        $queueMessage = new AMQPMessage($this->serializer->serialize($msg));
        $channel->basic_publish($queueMessage, '', $queueName);
        $channel->close();
    }

    public function handle(callable $callback, string $queueName = self::DEFAULT_QUEUE_NAME)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, false, false, false);

        $channel->basic_consume($queueName, '', false, true,false, false, $callback);

        while($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}
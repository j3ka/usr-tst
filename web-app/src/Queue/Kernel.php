<?php

namespace App\Queue;

use App\Queue\UserImport\Handler;
use App\Service\QueueService;
use Lib\App\Config\ConfigInterface;
use Lib\App\Container;
use Lib\App\KernelInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Kernel implements KernelInterface
{
    private const DEFAULT_QUEUE_NAME = 'import';

    /**
     * @var string
     */
    private string $projectDir;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @param string $projectDir
     * @param ConfigInterface $config
     */
    public function __construct(string $projectDir, ConfigInterface $config)
    {
        $this->projectDir = $projectDir;
        $this->config = $config;
    }

    public function handle()
    {
        $container = new Container($this, $this->config);

        $queueService = $container->get(QueueService::class);
        $callback = function (AMQPMessage $msg) use($container) {
            echo ' [*] Receive '.$msg->getBody().PHP_EOL;
            /** @var Handler $handler */
            $handler = $container->get(Handler::class);
            $handler->handle($msg->getBody());
            echo ' [+]  Processed '.$msg->getBody().PHP_EOL;
        };
        $queueService->handle($callback);
    }

    public function getProjectDir(): string
    {
        return $this->projectDir;
    }
}
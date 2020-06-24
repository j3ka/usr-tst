<?php

namespace App\Queue\UserImport;

use App\Api\ImportUsersCSV\Command;
use App\Api\ImportUsersCSV\Handler as ImportHandler;
use Lib\App\SerializerInterface;

class Handler
{
    /**
     * @var ImportHandler
     */
    private ImportHandler $handler;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * Handler constructor.
     * @param ImportHandler $handler
     * @param SerializerInterface $serializer
     */
    public function __construct(ImportHandler $handler, SerializerInterface $serializer)
    {
        $this->handler = $handler;
        $this->serializer = $serializer;
    }

    /**
     * @param string $msg
     */
    public function handle(string $msg)
    {
        $data = $this->serializer->unserialize($msg);
        $fileName = $data['fileName'] ?? null;
        if (null === $fileName) {
            throw new \RuntimeException('Invalid message');
        }

        $cmd = new Command($fileName);
        ($this->handler)($cmd);
    }
}
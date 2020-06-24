<?php

namespace Tests\App\Queue\UserImport;

use App\Api\ImportUsersCSV\Handler as ImportHandler;
use App\Queue\UserImport\Handler;
use App\Service\JsonSerializer;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    public function testEmptyInput()
    {
        $importHandler = $this->createMock(ImportHandler::class);
        $serializer = new JsonSerializer();

        $handler = new Handler($importHandler, $serializer);

        $this->expectException(\RuntimeException::class);

        $handler->handle('');
    }

    public function testWrongInput()
    {
        $importHandler = $this->createMock(ImportHandler::class);
        $serializer = new JsonSerializer();

        $handler = new Handler($importHandler, $serializer);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid message');

        $handler->handle(json_encode(['aaa']));
    }
}

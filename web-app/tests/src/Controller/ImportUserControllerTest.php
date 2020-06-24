<?php

namespace Tests\App\Controller;

use App\Api\MoveImportFile\Handler;
use App\Controller\ImportUsersController;
use App\Service\JsonSerializer;
use App\Service\QueueService;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ImportUserControllerTest extends TestCase
{
    private const FILE_NAME = __DIR__.'/test';

    public static function setUpBeforeClass(): void
    {
        file_put_contents(self::FILE_NAME, 'test');
    }

    public static function tearDownAfterClass(): void
    {
        if (file_exists(self::FILE_NAME)) {
            unlink(self::FILE_NAME);
        }
    }

    public function testEmptyRequest()
    {

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUploadedFiles')->willReturn([]);
        $response = $this->getController()->handle($request);

        $this->assertTrue($response instanceof ResponseInterface);
        $this->assertTrue($response->getStatusCode() === 400);
        $this->assertJson($response->getBody());
        $responseBody = json_decode($response->getBody(), true);
        $this->assertTrue(is_array($responseBody));
        $this->assertTrue(isset($responseBody['error']));
    }

    public function testHandle()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUploadedFiles')->willReturn([
            'users' => ['tmp_name' => self::FILE_NAME],
        ]);
        $response = $this->getController()->handle($request);

        $this->assertTrue($response->getStatusCode() === 200);
    }

    private function getController(): ImportUsersController
    {
        $queueService = $this->createMock(QueueService::class);
        $serializer = new JsonSerializer();
        $handler = $this->createMock(Handler::class);

        return new ImportUsersController($queueService, $serializer, $handler);
    }
}
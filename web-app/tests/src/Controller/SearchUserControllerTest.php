<?php

namespace Tests\App\Controller;

use App\Controller\SearchUserController;
use App\Repository\UserRepository;
use App\Service\JsonSerializer;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class SearchUserControllerTest extends TestCase
{
    public function testEmptyRequest()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([]);
        $response = $this->getController()->handle($request);

        $this->assertTrue($response->getStatusCode() === 400);
        $this->assertTrue(isset(json_decode($response->getBody(), true)['error']));
    }

    public function testNotFound()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([
            'field' => 'email',
            'value' => 'test',
        ]);
        $response = $this->getController()->handle($request);

        $this->assertTrue($response->getStatusCode() === 404);
    }

    public function testHandle()
    {
        $repository = $this->createMock(UserRepository::class);
        $repository->method('search')->willReturn([
            'test'
        ]);
        $serializer = new JsonSerializer();
        $controller = new SearchUserController($repository, $serializer);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')->willReturn([
            'field' => 'email',
            'value' => 'test',
        ]);

        $response = $controller->handle($request);

        $this->assertTrue($response->getStatusCode() === 200);
    }

    private function getController(): SearchUserController
    {
        $repository = $this->createMock(UserRepository::class);
        $repository->method('search')->willReturn([]);
        $serializer = new JsonSerializer();

        return new SearchUserController($repository, $serializer);
    }
}
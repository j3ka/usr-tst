<?php

namespace App\Controller;

use App\Api\MoveImportFile\Command;
use App\Api\MoveImportFile\Handler;
use App\Form\UsersImportForm;
use App\Queue\UserImport\Message;
use App\Service\QueueService;
use Lib\App\SerializerInterface;
use Lib\Form\FormError;
use Lib\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ImportUsersController implements RequestHandlerInterface
{
    /**
     * @var QueueService
     */
    private QueueService $queueService;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var Handler
     */
    private Handler $moveFileHandler;

    /**
     * ImportUsersController constructor.
     * @param QueueService $queueService
     * @param SerializerInterface $serializer
     * @param Handler $handler
     */
    public function __construct(
        QueueService $queueService,
        SerializerInterface $serializer,
        Handler $handler
    ){
        $this->queueService = $queueService;
        $this->serializer = $serializer;
        $this->moveFileHandler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $form = new UsersImportForm();
        $form->validate($request->getUploadedFiles());

        if (!$form->isValid()) {
            $msg = '';
            /** @var FormError $error */
            foreach ($form->getErrors() as $error) {
                $msg .= $error->getMessage().';';
            }
            return new Response(
                400,
                $this->serializer->serialize(['error' => $msg]),
                ['Content-Type: application/json']
            );
        }

        $tmpFileName = $form->getClearedData()['users']['tmp_name'];
        try {
            $fileName = ($this->moveFileHandler)(new Command($tmpFileName));
        } catch (\Throwable $e) {
            return new Response(
                422,
                $this->serializer->serialize(['error' => $e->getMessage()]),
                ['Content-Type: application/json']
            );
        }

        $this->queueService->send(new Message($fileName));

        return new Response(200, '', ['Content-Type: application/json']);
    }
}
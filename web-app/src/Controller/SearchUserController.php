<?php

namespace App\Controller;

use App\Form\UserSearchForm;
use App\Repository\UserRepository;
use Lib\App\SerializerInterface;
use Lib\Form\FormError;
use Lib\Http\Message\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SearchUserController implements RequestHandlerInterface
{

    /**
     * @var UserRepository
     */
    private UserRepository $repository;
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * SearchUserController constructor.
     * @param UserRepository $repository
     * @param SerializerInterface $serializer
     */
    public function __construct(UserRepository $repository, SerializerInterface $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $form = new UserSearchForm();
        $form->validate($request->getParsedBody());
        if (!$form->isValid()) {
            $msg = '';
            /** @var FormError $error */
            foreach ($form->getErrors() as $error) {
                $msg .= $error->getMessage().';';
            }
            return $this->getResponse(['error' => $msg], 400);
        }

        $users = $this->repository->search(
            $form->getClearedData('field'),
            $form->getClearedData('value'),
            30
        );
        $statusCode = 200;
        if (empty($users)) {
            $statusCode = 404;
        }

        return $this->getResponse(['data' => $users], $statusCode);
    }

    /**
     * @param $data
     * @param int $status
     * @return Response
     */
    private function getResponse($data, int $status): Response
    {
        return new Response(
            $status,
            $this->serializer->serialize($data),
            ['Content-Type: application/json']
        );
    }
}
<?php

namespace Lib\App;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    /**
     * @var ServiceResolver
     */
    private ServiceResolver $resolver;

    /**
     * Container constructor.
     * @param ServiceResolver $resolver
     */
    public function __construct(ServiceResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed Entry.
     * @throws ContainerExceptionInterface
     *
     * @throws NotFoundExceptionInterface
     */
    public function get($id)
    {
        return $this->resolver->resolve($id);
    }

    /**
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id): bool
    {
        return true;
    }
}

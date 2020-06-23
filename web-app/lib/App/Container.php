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
     * @var array
     */
    private array $services;

    /**
     * Container constructor.
     * @param ServiceResolver $resolver
     */
    public function __construct(ServiceResolver $resolver)
    {
        $this->resolver = $resolver;
        $this->services = [];
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
        if (!isset($this->services[$id])) {
            $this->services[$id] = $this->resolver->resolve($id);
        }

        return $this->services[$id];
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

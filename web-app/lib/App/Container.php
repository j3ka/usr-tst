<?php

namespace Lib\App;

use Lib\App\Config\ConfigInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private array $services;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * Container constructor.
     * @param KernelInterface $kernel
     * @param ConfigInterface $config
     */
    public function __construct(KernelInterface $kernel, ConfigInterface $config)
    {
        $this->config = $config;
        $this->services = [];

        $this->services[KernelInterface::class] = $kernel;
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
            $this->services[$id] = $this->resolve($id);
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
        return isset($this->services[$id]);
    }

    /**
     * @param string $className
     * @return mixed|object
     * @throws ReflectionException
     */
    private function resolve(string $className)
    {
        $ref = new ReflectionClass($className);
        if ($ref->isInterface()) {
            $className = $this->config->resolveInterface($className);
            $ref = new ReflectionClass($className);
        }
        $constructor = $ref->getConstructor();
        if (null === $constructor) {
            return new $className();
        }
        if ($constructor->isAbstract()) {
            throw new RuntimeException('Can not create abstract class');
        }
        if ($constructor->isPrivate()) {
            throw new RuntimeException('Can not create singleton class');
        }
        if (
            $constructor->getNumberOfParameters() === 1
            && $constructor->getParameters()[0]->getName() === 'argument'
        ) {
            return new $className();
        }
        $args = [];
        $argsCollection = $this->config->getArgumentsForClass($className);

        foreach ($constructor->getParameters() as $arg) {
            $argClass = $arg->getClass();
            if (null === $argClass) {
                $argName = $arg->getName();
                if (null !== $argsCollection) {
                    $findedArg = $argsCollection->getArgument($argName);
                    if (null === $findedArg ) {
                        if ($arg->isOptional()) {
                            break;
                        }
                        throw new RuntimeException('Argument "'.$argName.'" for service "'.$className.'" not defined');
                    }

                    $args[] = $findedArg;
                }
            } else {
                $args[] = $this->get($argClass->getName());
            }
        }

        return $ref->newInstanceArgs($args);
    }
}

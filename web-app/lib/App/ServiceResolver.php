<?php

namespace Lib\App;

use Lib\App\Config\ConfigInterface;
use ReflectionClass;
use RuntimeException;

class ServiceResolver
{
    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * Container constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $className
     * @return mixed|object
     * @throws \ReflectionException
     */
    public function resolve(string $className)
    {
        $ref = new ReflectionClass($className);
        if ($ref->isInterface()) {
            $className = $this->config->resolveInteface($className);
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
                $argType = $arg->getType();
                $argName = $arg->getName();
                if (null !== $argsCollection) {
                    $args[] = $argsCollection->getArgument($argName);
                }
            } else {
                $args[] = $this->resolve($argClass->getName());
            }
        }

        return $ref->newInstanceArgs($args);
    }
}
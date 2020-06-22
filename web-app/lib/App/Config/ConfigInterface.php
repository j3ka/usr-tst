<?php

namespace Lib\App\Config;

interface ConfigInterface
{
    /**
     * @param string $interfaceName
     *
     * @return string
     */
    public function resolveInterface(string $interfaceName): string;

    /**
     * @param string $className
     *
     * @return ArgumentCollection|null
     */
    public function getArgumentsForClass(string $className): ?ArgumentCollection;
}

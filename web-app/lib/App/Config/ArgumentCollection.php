<?php

namespace Lib\App\Config;

class ArgumentCollection
{
    /**
     * @var array
     */
    private array $arguments;

    public function __construct()
    {
        $this->arguments = [];
    }

    /**
     * @param string $argumentName
     * @param string $argumentValue
     */
    public function setArgument(string $argumentName, string $argumentValue): void
    {
        $this->arguments[$argumentName] = $argumentValue;
    }

    /**
     * @param string $argumentName
     *
     * @return mixed
     */
    public function getArgument(string $argumentName): ?string
    {
        if (!isset($this->arguments[$argumentName])) {
            return null;
        }

        return $this->arguments[$argumentName];
    }
}

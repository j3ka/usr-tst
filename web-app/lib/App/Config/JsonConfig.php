<?php

namespace Lib\App\Config;

class JsonConfig implements ConfigInterface
{
    private const DEFAULT_ARGUMENT_SOURCE = 'env',
                  ENV_SORUCE = 'env';
    /**
     * @var array
     */
    private array $interfacesMapping;

    /**
     * @var array
     */
    private array $argumentsMapping;


    /**
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->interfacesMapping = [];
        $this->argumentsMapping = [];

        if (!file_exists($fileName)) {
            throw new \InvalidArgumentException('File '.$fileName.' does not exists');
        }
        $content = file_get_contents($fileName);
        $config = json_decode($content, true);
        if (null === $config) {
            throw new \RuntimeException(json_last_error_msg());
        }

        if (isset($config['interfaces'])) {
            $this->interfacesMapping = $config['interfaces'];
        }

        if (isset($config['arguments'])) {
            $this->argumentsMapping = $config['arguments'];
        }
    }

    /**
     * @param string $interfaceName
     *
     * @return string
     */
    public function resolveInterface(string $interfaceName): string
    {
        if (!isset($this->interfacesMapping[$interfaceName])) {
            throw new \RuntimeException('Can not find class for interface '.$interfaceName);
        }

        return $this->interfacesMapping[$interfaceName];
    }

    /**
     * @param string $className
     *
     * @return ArgumentCollection|null
     */
    public function getArgumentsForClass(string $className): ?ArgumentCollection
    {
        if (!isset($this->argumentsMapping[$className])) {
            return null;
        }
        
        $argumentCollection = new ArgumentCollection();
        foreach ($this->argumentsMapping[$className] as $argument) {
            if (!isset($argument['name'])) {
                throw new \RuntimeException('No name for argument for class '.$className);
            }
            $name = $argument['name'];
            $source = $argument['source'] ?? self::DEFAULT_ARGUMENT_SOURCE;
            if ($source === self::ENV_SORUCE) {
                $argumentCollection->setArgument($name, $_ENV[$name] ?? '');
                continue;
            }

            throw new \RuntimeException('Undefined source type for argument '.$name);
        }

        return $argumentCollection;
    }
}

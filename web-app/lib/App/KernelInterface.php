<?php

namespace Lib\App;

use Lib\App\Config\ConfigInterface;
use Psr\Container\ContainerInterface;

interface KernelInterface
{
    /**
     * @param string $projectDir
     * @param ConfigInterface $config
     */
    public function __construct(string $projectDir, ConfigInterface $config);

    public function handle();

    /**
     * @return string
     */
    public function getProjectDir(): string;
}
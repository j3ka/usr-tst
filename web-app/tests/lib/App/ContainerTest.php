<?php

namespace Tests\Lib\App;

use Lib\App\Config\ConfigInterface;
use Lib\App\Container;
use Lib\App\KernelInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerTest extends TestCase
{
    public function testKernelExistsInContainer()
    {
        $this->assertTrue($this->getContainer()->has(KernelInterface::class));
        $this->assertTrue(
            $this->getContainer()->get(KernelInterface::class)
            instanceof KernelInterface
        );
    }

    private function getContainer(): ContainerInterface
    {
        $kernel = $this->createMock(KernelInterface::class);
        $config = $this->createMock(ConfigInterface::class);
        return new Container($kernel, $config);
    }
}
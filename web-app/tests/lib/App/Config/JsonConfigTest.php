<?php

namespace Tests\Lib\App\Config;

use \InvalidArgumentException;
use Lib\App\Config\JsonConfig;
use PHPUnit\Framework\TestCase;

class JsonConfigTest extends TestCase
{
    private const INVALID_FILE_PATH = __DIR__.'/wrong',
                  VALID_FILE_PATH   = __DIR__.'/valid',
                  INTERFACE_NAME    = 'SomeInterface',
                  CLASS_NAME        = 'SomeClassName'
            ;

    public static function setUpBeforeClass(): void
    {
        file_put_contents(self::INVALID_FILE_PATH, 'aaa');
        file_put_contents(self::VALID_FILE_PATH,
            '{"interfaces": {"'
            .self::INTERFACE_NAME.'":"'.self::CLASS_NAME
            .'"}}'
        );
    }

    public static function tearDownAfterClass(): void
    {
        foreach ([self::VALID_FILE_PATH, self::INVALID_FILE_PATH] as $fileName) {
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
    }

    public function testWrongFile()
    {
        $this->expectException(InvalidArgumentException::class);
        $conf = new JsonConfig('');

        $this->expectException(\RuntimeException::class);
        $conf = new JsonConfig(self::INVALID_FILE_PATH);
    }

    public function testResolveInterface()
    {
        $conf = new JsonConfig(self::VALID_FILE_PATH);
        $this->assertEquals(
            self::CLASS_NAME,
            $conf->resolveInterface(self::INTERFACE_NAME)
        );
    }
}
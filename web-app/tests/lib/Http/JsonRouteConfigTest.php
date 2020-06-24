<?php

namespace Tests\Lib\Http;

use Lib\Http\JsonRouteConfig;
use PHPUnit\Framework\TestCase;

class JsonRouteConfigTest extends TestCase
{
    private const INVALID_FILE_NAME = __DIR__.'/wrong',
                  VALID_FILE_NAME   = __DIR__.'/valid',
                  MATCHED_KEY       = 'matched';

    public static function setUpBeforeClass(): void
    {
        file_put_contents(self::INVALID_FILE_NAME, 'test');
        file_put_contents(self::VALID_FILE_NAME,
            '{"def": "SomeClass", "'
            .self::MATCHED_KEY.'": "AnotherClass"}'
        );
    }

    public static function tearDownAfterClass(): void
    {
        foreach ([self::VALID_FILE_NAME, self::INVALID_FILE_NAME] as $fileName) {
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
    }

    public function testWrongFile()
    {
        $this->expectException(\InvalidArgumentException::class);
        new JsonRouteConfig('');

        $this->expectException(\RuntimeException::class);
        new JsonRouteConfig(self::INVALID_FILE_NAME);
    }

    public function testConstruct()
    {
        $conf = new JsonRouteConfig(self::VALID_FILE_NAME);

        $this->assertTrue(!empty($conf->getRoutesCollection()));
    }
}
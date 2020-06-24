<?php

namespace Tests\Lib\Reader;

use Lib\Reader\CSVReader;
use PHPUnit\Framework\TestCase;

class CSVReaderTest extends TestCase
{
    private const INVALID_FILE_NAME = __DIR__.'/wrong',
                  VALID_FILE_NAME   = __DIR__.'/valid',
                  VALID_CONTENT = 'User Id,Username,Email,Currency,Total';

    public static function setUpBeforeClass(): void
    {
        file_put_contents(self::INVALID_FILE_NAME, 'test');
        file_put_contents(self::VALID_FILE_NAME, self::VALID_CONTENT.PHP_EOL);
    }

    public static function tearDownAfterClass(): void
    {
        foreach ([self::VALID_FILE_NAME, self::INVALID_FILE_NAME] as $fileName) {
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
    }

    public function testInvalidFile()
    {
        $this->expectException(\InvalidArgumentException::class);
        new CSVReader('');
    }

    public function testWrongFile()
    {
        $reader = new CSVReader(self::INVALID_FILE_NAME);
        $result = [];

        for (;;) {
            $data = $reader->parseChunked();
            if (empty($data)) {
                break;
            }
            $result[] = $data;
        }

        $this->assertEmpty($result);
    }

    public function testParse()
    {
        $reader = new CSVReader(self::VALID_FILE_NAME);
        $result = [];

        for (;;) {
            $data = $reader->parseChunked();
            if (empty($data)) {
                break;
            }
            $result[] = $data;
        }

        // 'User Id,Username,Email,Currency,Total'.PHP_EOL
        $this->assertEquals($result[0][0], explode(',', self::VALID_CONTENT));
    }
}
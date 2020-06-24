<?php

namespace Tests\App\Api\MoveImportFile;

use App\Api\MoveImportFile\Command;
use App\Api\MoveImportFile\Handler;
use Lib\App\KernelInterface;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    public const FILE_PATH = __DIR__.'/test';

    public static string $newFileName = __DIR__.'/test2';

    public static function setUpBeforeClass(): void
    {
        file_put_contents(self::FILE_PATH, 'test', 0777);
    }

    public static function tearDownAfterClass(): void
    {
        foreach ([self::FILE_PATH, self::$newFileName] as $fileName) {
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
    }

    public function testInvokeNotUploadedFile()
    {
        $cmd = new Command(self::FILE_PATH);
        $kernel = $this->createMock(KernelInterface::class);
        $kernel
            ->method('getProjectDir')
            ->willReturn(__DIR__);
        $handler = new Handler($kernel);
        $this->expectExceptionMessage('File is not uploadable');
        $fileName = ($handler)($cmd);
    }
}
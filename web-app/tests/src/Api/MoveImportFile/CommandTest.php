<?php

namespace Tests\App\Api\MoveImportFile;

use App\Api\MoveImportFile\Command;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testConstruct()
    {
        $fileName = 'test';
        $cmd = new Command($fileName);
        $this->assertEquals($cmd->getFileName(), $fileName);
    }
}
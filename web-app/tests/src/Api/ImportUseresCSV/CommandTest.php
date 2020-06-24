<?php

namespace Tests\App\Api\ImportUseresCSV;

use App\Api\ImportUsersCSV\Command;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testCommand()
    {
        $fileName = 'test';
        $chunkSize = 2;

        $cmd = new Command($fileName, $chunkSize);
        $this->assertTrue($cmd->getFileName() === $fileName);
        $this->assertTrue($cmd->getChunkSize() === $chunkSize);

        $cmd = new Command($fileName);
        $this->assertTrue($cmd->getFileName() === $fileName);
        $this->assertTrue($cmd->getChunkSize() === Command::DEFAULT_CHUNK_SIZE);
    }
}
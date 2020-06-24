<?php

namespace Tests\App\Queue\UserImport;

use App\Queue\UserImport\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testSerialize()
    {
        $fileName = 'test';
        $msg = new Message($fileName);

        $this->assertEquals(
            ['fileName' => $fileName],
            $msg->jsonSerialize()
        );
    }
}
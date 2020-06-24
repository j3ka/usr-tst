<?php

namespace Tests\App\Service;

use App\Service\JsonSerializer;
use PHPUnit\Framework\TestCase;

class JsonSerializerTest extends TestCase
{
    public function testEmptyImput()
    {
        $this->expectException(\RuntimeException::class);

        $serializer = new JsonSerializer();

        $serializer->serialize('');
        $serializer->serialize(null);
        $serializer->unserialize('');
    }

    public function testSerialize()
    {
        $serializer = new JsonSerializer();

        $example = ['aaa' => 'bbb'];

        $this->assertEquals($serializer->serialize($example), json_encode($example));
    }

    public function testUnserialize()
    {
        $serializer = new JsonSerializer();
        $example = '{"aaa": "bbb"}';

        $this->assertEquals($serializer->unserialize($example), json_decode($example, true));
    }
}
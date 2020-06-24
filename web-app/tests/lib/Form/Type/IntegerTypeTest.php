<?php

namespace Tests\Lib\Form\Type;

use Lib\Form\Type\IntegerType;
use PHPUnit\Framework\TestCase;

class IntegerTypeTest extends TestCase
{
    public function testInvalidData()
    {
        $type = new IntegerType();
        $this->assertFalse($type->validate(1.12));
        $this->assertFalse($type->validate('asdas'));
        $this->assertFalse($type->validate(null));
        $this->assertFalse($type->validate([]));
    }

    public function testValidate()
    {
        $type = new IntegerType();
        $this->assertTrue($type->validate(12345));
    }
}
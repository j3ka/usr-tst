<?php

namespace Tests\Lib\Form\Type;

use Lib\Form\Type\FloatType;
use PHPUnit\Framework\TestCase;

class FloatTypeTest extends TestCase
{
    public function testInvalidData()
    {
        $type = new FloatType();
        $this->assertFalse($type->validate(112));
        $this->assertFalse($type->validate('asdas'));
        $this->assertFalse($type->validate(null));
        $this->assertFalse($type->validate([]));
    }

    public function testValidate()
    {
        $type = new FloatType();
        $this->assertTrue($type->validate(123.45));
    }
}
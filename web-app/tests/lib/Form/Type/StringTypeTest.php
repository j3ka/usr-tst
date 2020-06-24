<?php

namespace Tests\Lib\Form\Type;

use Lib\Form\Type\StringType;
use PHPUnit\Framework\TestCase;

class StringTypeTest extends TestCase
{
    public function testInvalidData()
    {
        $type = new StringType();
        $this->assertFalse($type->validate(112));
        $this->assertFalse($type->validate(''));
        $this->assertFalse($type->validate(null));
        $this->assertFalse($type->validate([]));
    }

    public function testValidate()
    {
        $type = new StringType();
        $this->assertTrue($type->validate('SPAM'));
    }
}
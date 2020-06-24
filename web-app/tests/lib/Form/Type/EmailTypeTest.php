<?php

namespace Tests\Lib\Form\Type;

use Lib\Form\Type\EmailType;
use PHPUnit\Framework\TestCase;

class EmailTypeTest extends TestCase
{
    public function testInvalidInput()
    {
        $type = new EmailType();
        $this->assertFalse($type->validate('asaaa'));
        $this->assertFalse($type->validate('adasd@asdkasj'));
        $this->assertFalse($type->validate(1234));
        $this->assertFalse($type->validate([]));
        $this->assertFalse($type->validate(null));
        $this->assertFalse($type->validate(''));
    }

    public function testValidate()
    {
        $type = new EmailType();
        $this->assertTrue($type->validate('example@test.com'));
        $this->assertTrue($type->validate('some.asd@test.ca'));
    }
}
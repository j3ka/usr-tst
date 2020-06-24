<?php

namespace Tests\App\Form;

use App\Form\UserForm;
use PHPUnit\Framework\TestCase;

class UserFormTest extends TestCase
{
    public function testInvalidTypes()
    {
        $form = new UserForm();
        $data = [
            'id'       => 'test',
            'username' => 12345,
            'email'    => [],
            'currency' => new \StdClass(),
            'total'    => 100,
        ];
        $form->validate($data);

        $this->assertFalse($form->isValid());
    }

    public function testMissingFields()
    {
        $form = new UserForm();
        $data = [
            'id'       => 123,
            'total'    => 100.0,
        ];
        $form->validate($data);

        $this->assertFalse($form->isValid());
    }

    public function testValidate()
    {
        $form = new UserForm();
        $data = [
            'id'       => 12345,
            'username' => 'Test',
            'email'    => 'test@example.com',
            'currency' => 'USD',
            'total'    => 9999.00,
        ];
        $form->validate($data);

        $this->assertTrue($form->isValid());
    }
}
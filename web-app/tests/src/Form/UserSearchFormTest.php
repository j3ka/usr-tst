<?php

namespace Tests\App\Form;

use App\Form\UserSearchForm;
use PHPUnit\Framework\TestCase;

class UserSearchFormTest extends TestCase
{
    public function testEmptyData()
    {
        $form = new UserSearchForm();
        $form->validate([]);

        $this->assertFalse($form->isValid());
    }

    public function testInvalidTypes()
    {
        $form = new UserSearchForm();
        $form->validate([
            'field' => 12345,
            'value' => [],
        ]);

        $this->assertFalse($form->isValid());
    }

    public function testWrongField()
    {
        $form = new UserSearchForm();
        $form->validate([
            'field' => 'SPAM',
            'value' => 'some'
        ]);

        $this->assertFalse($form->isValid());
    }

    public function testValidateFieldEmail()
    {
        $form = new UserSearchForm();
        $form->validate([
            'field' => 'email',
            'value' => 'some'
        ]);

        $this->assertTrue($form->isValid());
    }

    public function testValidateFieldName()
    {
        $form = new UserSearchForm();
        $form->validate([
            'field' => 'username',
            'value' => 'some'
        ]);

        $this->assertTrue($form->isValid());
    }
}
<?php

namespace App\Form;

use Lib\Form\AbstractForm;
use Lib\Form\Type\EmailType;
use Lib\Form\Type\FloatType;
use Lib\Form\Type\IntegerType;
use Lib\Form\Type\StringType;

class UserForm extends AbstractForm
{
    /**
     * @return array
     */
    public function getTypes(): array
    {
        return [
            'id'       => new IntegerType(),
            'username' => new StringType(),
            'email'    => new EmailType(),
            'currency' => new StringType(),
            'total'    => new FloatType(),
        ];
    }
}
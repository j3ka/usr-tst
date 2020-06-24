<?php

namespace App\Form;

use Lib\Form\AbstractForm;
use Lib\Form\FormError;
use Lib\Form\Type\StringType;

class UserSearchForm extends AbstractForm
{
    /**
     * @return array
     */
    public function getTypes(): array
    {
        return [
            'field' => new StringType(),
            'value' => new StringType(),
        ];
    }

    public function validate(array $inputData): void
    {
        parent::validate($inputData);
        if (!in_array($this->getClearedData('field'), ['email', 'username'])) {
            $this->errors[] = new FormError('Field "field" must be equals "email" or "username"');
        }
    }
}
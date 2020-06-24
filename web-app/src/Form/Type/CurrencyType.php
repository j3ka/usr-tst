<?php

namespace App\Form\Type;

use Lib\Form\Type\StringType;

class CurrencyType extends StringType
{
    public function validate($data): bool
    {
        if(!parent::validate($data)) {
            return false;
        }

        return preg_match('/[A-Z]{3}/', $data) === 1;
    }

    public function clearData($data)
    {
        $data = parent::clearData($data);

        return strtoupper($data);
    }
}
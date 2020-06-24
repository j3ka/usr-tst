<?php

namespace Lib\Form\Type;

use Lib\Form\TypeInterface;

class EmailType extends StringType implements TypeInterface
{
    public function validate($data): bool
    {
        if (!is_string($data)) {
            return false;
        }

        return preg_match('/[a-z0-9.]{3,}@[a-z0-9]{3,}\.[a-z]{2,4}/', $data) === 1;
    }
}
<?php

namespace Lib\Form\Type;

use Lib\Form\TypeInterface;

class IntegerType implements TypeInterface
{
    /**
     * @param $data
     * @return bool
     */
    public function validate($data): bool
    {
        if(!is_int($data)) {
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return int|mixed
     */
    public function clearData($data)
    {
        if (is_numeric($data)) {
            return (int)$data;
        }

        return $data;
    }
}
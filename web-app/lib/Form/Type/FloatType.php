<?php

namespace Lib\Form\Type;

use Lib\Form\TypeInterface;

class FloatType implements TypeInterface
{
    /**
     * @param $data
     * @return bool
     */
    public function validate($data): bool
    {
        if (!is_float($data)) {
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return float|mixed
     */
    public function clearData($data)
    {
        if (is_numeric($data)) {
            return (float)$data;
        }

        return $data;
    }
}
<?php

namespace Lib\Form\Type;

use Lib\Form\TypeInterface;

class StringType implements TypeInterface
{
    /**
     * @param $data
     * @return bool
     */
    public function validate($data): bool
    {
        if (!is_string($data) || empty($data)) {
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return mixed|string
     */
    public function clearData($data)
    {
        if (!is_string($data)) {
            return '';
        }
        $res = preg_replace('/[^a-zA-Z0-9\'@.]/', '', (string)$data);
        return addslashes($res);
    }
}
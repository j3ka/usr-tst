<?php

namespace Lib\Form;

interface TypeInterface
{
    /**
     * @param $data
     * @return bool
     */
    public function validate($data): bool;

    /**
     * @param $data
     * @return mixed
     */
    public function clearData($data);
}
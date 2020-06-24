<?php


namespace Lib\Form\Type;


use Lib\Form\TypeInterface;

abstract class FileType implements TypeInterface
{
    /**
     * @param $data
     * @return bool
     */
    public function validate($data): bool
    {
        $type = mime_content_type($data['tmp_name']);
        if (in_array($type, $this->getValidMimeTypes())) {
            return true;
        }

        return false;
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function clearData($data)
    {
        return $data;
    }

    /**
     * @return array
     */
    abstract public function getValidMimeTypes(): array;
}
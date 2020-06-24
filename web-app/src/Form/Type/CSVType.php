<?php


namespace App\Form\Type;


use Lib\Form\Type\FileType;

class CSVType extends FileType
{
    /**
     * @return array|string[]
     */
    public function getValidMimeTypes(): array
    {
        return [
            'text/comma-separated-values',
            'text/csv',
            'application/csv',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'text/plain',
        ];
    }
}
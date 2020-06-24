<?php

namespace App\Form;

use App\Form\Type\CSVType;
use Lib\Form\AbstractForm;

class UsersImportForm extends AbstractForm
{
    /**
     * @return CSVType[]|array
     */
    public function getTypes(): array
    {
        return [
            'users' => new CSVType(),
        ];
    }
}
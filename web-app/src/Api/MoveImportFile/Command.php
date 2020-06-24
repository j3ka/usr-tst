<?php

namespace App\Api\MoveImportFile;

class Command
{
    /**
     * @var string
     */
    private string $fileName;

    /**
     * Command constructor.
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}
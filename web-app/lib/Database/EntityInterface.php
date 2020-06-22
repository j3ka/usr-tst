<?php

namespace Lib\Database;

interface EntityInterface
{
    /**
     * @return string
     */
    public function getTableName(): string;

    /**
     * @return array
     */
    public function getColumnMap(): array;
}

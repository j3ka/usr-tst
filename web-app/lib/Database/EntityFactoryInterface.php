<?php

namespace Lib\Database;

interface EntityFactoryInterface
{
    /**
     * @param array $entityData
     *
     * @return EntityInterface
     */
    public function arrayToEntity(array $entityData): EntityInterface;

    /**
     * @param array $entitiesData
     *
     * @return array
     */
    public function arrayToEntityMultiple(array $entitiesData): array;
}

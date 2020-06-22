<?php

namespace Lib\Database;

use Lib\Database\EntityInterface;
use \InvalidArgumentException;
use \ReflectionClass;

class EntityFieldsResolver
{
    /**
     * @param EntityInterface $entity
     *
     * @return array
     */
    public function getFieldsHash(EntityInterface $entity): array
    {
        $fieldsHash = [];
        $publicVars = get_object_vars($entity);
        foreach ($entity->getColumnMap() as $propertyName => $fieldName) {
            $methodName = 'get'.ucfirst($propertyName);
            if (isset($publicVars[$propertyName])) {
                $fieldsHash[$fieldName] = $entity->{$propertyName};
                continue;
            } else if (method_exists($entity, $methodName)) {
                $fieldsHash[$fieldName] = $entity->{$methodName}();
                continue;
            }
            throw new InvalidArgumentException('Property "'.$propertyName.'" or method "'.$methodName.'()" not exists in "'.get_class($entity).'"');
        }

        return $fieldsHash;
    }

    /**
     * @param string $entityClass
     *
     * @return array
     */
    public function getFieldList(string $entityClass): array
    {
        $entity = $this->getEntityInstance($entityClass);

        return array_keys($entity->getColumnMap());
    }

    public function getTableName(string $entityClass): string
    {
        $entity = $this->getEntityInstance($entityClass);

        return $entity->getTableName();
    }

    private function getEntityInstance(string $entityClass): EntityInterface
    {
        $ref = new ReflectionClass($entityClass);
        if (!in_array(EntityInterface::class, $ref->getInterfaceNames())) {
             throw new InvalidArgumentException('Class '.$entityClass.' must implement EntityInterface');
        }

        return $ref->newInstanceWithoutConstructor();
    }
}

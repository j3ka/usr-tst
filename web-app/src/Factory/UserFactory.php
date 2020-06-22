<?php

namespace App\Factory;

use App\Entity\User;
use Lib\Database\EntityFactoryInterface;
use Lib\Database\EntityFieldsResolver;
use \InvalidArgumentException;

class UserFactory implements EntityFactoryInterface
{
    /**
     * @var EntityFieldsResolver
     */
    private EntityFieldsResolver $fieldsResolver;

    /**
     * @param EntityFieldsResolver $fieldsResolver
     */
    public function __construct(EntityFieldsResolver $fieldsResolver)
    {
        $this->fieldsResolver = $fieldsResolver;
    }

    /**
     * @param array $data
     *
     * @return User
     */
    public function create(
        int    $id,
        string $username,
        string $email,
        string $currency,
        float  $total
    ): User
    {
        return new User($id, $username, $email, $currency, $total);
    }

    /**
     * @param array $entityData
     *
     * @throws InvalidArgumentException
     */
    public function arrayToEntity(array $entityData): User
    {
        foreach ($this->fieldsResolver->getFieldList(User::class) as $fieldName) {
            if (!isset($entityData[$fieldName])) {
                throw new InvalidArgumentException('There are no data for field "'.$fieldName.'"');
            }
        }

        return new User(
            $entityData['id'],
            $entityData['username'],
            $entityData['email'],
            $entityData['currency'],
            $entityData['total']
        );
    }

    /**
     * @param array $entitiesData
     *
     * @return array
     */
    public function arrayToEntityMultiple(array $entitiesData): array
    {
        $result = [];
        foreach($entitiesData as $entityData) {
            $result[] = $this->arrayToEntity($entityData);
        }

        return $result;
    }
}

<?php

namespace App\Repository;

use App\Entity\User;
use App\Factory\UserFactory;
use Lib\Database\Repository;
use Lib\Database\Session;

class UserRepository extends Repository
{
    /**
     * @param Session $sess
     * @param UserFactory $factory
     */
    public function __construct(Session $sess, UserFactory $factory)
    {
        parent::__construct($sess, $factory);
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return User::class;
    }

    public function searchByEmail(string $value, int $limit = 0)
    {
        return $this->search('email', $value, $limit);
    }

    public function searchByName(string $value, int $limit = 0)
    {
        return $this->search('username', $value, $limit);
    }

    private function search($fieldName, $fieldValue, int $limit = 0)
    {
        $result = $this->sess
             ->query($this->getEntityClass())
             ->searchBy($fieldName, $fieldValue)
             ->limit($limit)
             ->all();

        return $this->factory->arrayToEntityMultiple($result);
    }
}

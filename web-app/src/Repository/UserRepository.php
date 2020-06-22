<?php

namespace App\Repository;

use App\Entity\User;
use App\Factory\UserFactory;
use Lib\App\CacheInterface;
use Lib\Database\Repository;
use Lib\Database\Session;

class UserRepository extends Repository
{
    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * @param Session $sess
     * @param UserFactory $factory
     * @param CacheInterface $cache
     */
    public function __construct(Session $sess, UserFactory $factory, CacheInterface $cache)
    {
        parent::__construct($sess, $factory);
        $this->cache = $cache;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return User::class;
    }

    /**
     * @param string $value
     * @param int $limit
     *
     * @return array
     */
    public function searchByEmail(string $value, int $limit = 0): array
    {
        return $this->search('email', $value, $limit);
    }

    /**
     * @param string $value
     * @param int $limit
     *
     * @return array
     */
    public function searchByName(string $value, int $limit = 0): array
    {
        return $this->search('username', $value, $limit);
    }

    /**
     * @param mixed $fieldName
     * @param mixed $fieldValue
     * @param int $limit
     *
     * @return array
     */
    private function search($fieldName, $fieldValue, int $limit = 0): array
    {
        $cacheKey = $fieldName.'_'.$fieldValue;
        
        if ($this->cache->exists($cacheKey)) {
            $result = $this->cache->get($cacheKey);
        } else {
            $result = $this->sess
                 ->query($this->getEntityClass())
                 ->searchBy($fieldName, $fieldValue)
                 ->limit($limit)
                 ->all();
            $this->cache->addToKey($cacheKey, $result);
        }

        return $this->factory->arrayToEntityMultiple($result);
    }
}

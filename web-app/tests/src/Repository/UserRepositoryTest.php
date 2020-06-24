<?php

namespace Tests\App\Repository;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use Lib\App\CacheInterface;
use Lib\Database\EntityFieldsResolver;
use Lib\Database\Query;
use Lib\Database\Session;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private const FROM_CACHE_RESULT = ['from_cache'],
                  FROM_DB_RESULT    = ['from_DB'];

    public function testEntityClass()
    {
        $this->assertEquals(
            $this->getRepository()->getEntityClass(),
            User::class
        );
    }

    public function testGetDataFromCache()
    {
        $result = $this->getRepository()->search('test', 'cache');

        $this->assertEquals(self::FROM_CACHE_RESULT, $result);
    }

    public function testGetDataFromDatabase()
    {
        $result = $this->getRepository()->search('test', 'db');

        $this->assertEquals(self::FROM_DB_RESULT, $result);
    }

    private function getRepository(): UserRepository
    {

        $query = $this->createMock(Query::class);
        $query->method('all')->willReturn(self::FROM_DB_RESULT);
        $query->method('searchBy')->willReturn($query);
        $query->method('limit')->willReturn($query);

        $sess = $this->createMock(Session::class);
        $sess->method('query')->willReturn($query);

        $factory = $this->createMock(UserFactory::class);
        $factory->method('arrayToEntityMultiple')->willReturnCallback(fn($obj) => $obj);
        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')->willReturn(self::FROM_CACHE_RESULT);
        $cache->method('exists')->willReturnCallback(fn($str) => $str === 'test_cache');

        return new UserRepository($sess, $factory, $cache);
    }
}
<?php

namespace App\Service;

use Lib\App\CacheInterface;
use Lib\App\SerializerInterface;
use Predis\Client;

class CacheService implements CacheInterface
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Client $reidsClient
     * @param SerializerInterface $serializer
     */
    public function __construct(Client $reidsClient, SerializerInterface $serializer)
    {
        $this->client = $reidsClient;
        $this->serializer = $serializer;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function get(string $key): array
    {
        $result = $this->client->transaction()->smembers($key)->execute();
        if (!isset($result[0][0])) {
            return [];
        }

        return $this->serializer->unserialize($result[0][0]);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function del(string $key): bool
    {
        if (!$this->client->exists($key)) {
            return false;
        }
        $this->client->transaction()->del($key);

        return true;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function addToKey(string $key, $value): bool
    {
        $value = $this->serializer->serialize($value);
        $this->client->sadd($key, $value);

        return true;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool
    {
        return $this->client->exists($key) === 1;
    }

    public function clearKeys(): bool
    {
        $res = $this->client->flushall();

        return true;
    }
}

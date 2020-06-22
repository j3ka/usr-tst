<?php

namespace App\Service;

use Lib\App\SerializerInterface;

class JsonSerializer implements SerializerInterface
{
    /**
     * @param mixed $object
     *
     * @return string
     */
    public function serialize($object): string
    {
        $result = json_encode($object);
        if (false === $result) {
            throw new \RuntimeException(json_last_error_msg());
        }

        return $result;
    }

    /**
     * @param string $encoded
     *
     * @return array
     */
    public function unserialize(string $encoded): array
    {
        $result = json_decode($encoded, true);
        if (null === $result) {
            throw new \RuntimeException(json_last_error_msg());
        }

        return $result;
    }
}

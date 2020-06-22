<?php

namespace Lib\App;

interface SerializerInterface
{
    /**
     * @param mixed $object
     *
     * @return string
     */
    public function serialize($object): string;

    /**
     * @param string $encoded
     *
     * @return array
     */
    public function unserialize(string $encoded): array;
}

<?php

namespace Lib\App;

interface CacheInterface
{
    /**
     * @param string $key
     *
     * @return array
     */
    public function get(string $key): array;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function del(string $key): bool;

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function addToKey(string $key, $value): bool;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool;

    /**
     * @return bool
     */
    public function clearKeys(): bool;
}

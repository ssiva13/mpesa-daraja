<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Contracts;

interface CacheStore
{
    /**
     * Get the cache value from the cache store or a default value to be supplied.
     *
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    public function get($key, $default = null);
    
    /**
     * Store an item in the cache store.
     *
     * @param string $key
     * @param mixed $value
     * @param \DateTimeInterface|\DateInterval|float|int $minutes
     */
    public function put(string $key, $value, $minutes = null);
}

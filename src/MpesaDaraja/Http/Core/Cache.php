<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\Core;

use Carbon\Carbon;
use Ssiva\MpesaDaraja\Contracts\CacheStore;

class Cache implements CacheStore
{
    private Config $config;
    
    public function __construct(Config $configuration)
    {
        $this->config = $configuration;
    }
    
    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        $driver = \trim($this->config->get('mpesa.cache.driver'));
        $directory = \trim($this->config->get('mpesa.cache.file.path'));
        if($driver === 'file'){
            $directory = \trim($this->config->get('mpesa.cache.file.path'));
        }
        $location = $directory . '/.mpc';
        
        if (!\is_file($location)) {
            return $default;
        }
        $cache = \unserialize(\file_get_contents($location));
        $cache = $this->cleanCache($cache, $location);
        
        if (!isset($cache[$key])) {
            return $default;
        }
        
        return $cache[$key]['v'];
    }
    
    /**
     * @inheritDoc
     */
    public function put(string $key, $value, Carbon $dateTime = null)
    {
        $driver = \trim($this->config->get('mpesa.cache.driver'));
        $directory = \trim($this->config->get('mpesa.cache.file.path'));
        if($driver === 'file'){
            $directory = \trim($this->config->get('mpesa.cache.file.path'));
        }
        $location = $directory . '/.mpc';
        
        if (!\is_dir($directory)) {
            \mkdir($directory, 0755, true);
        }
        $initial = [];
        if (\is_file($location)) {
            $initial = \unserialize(\file_get_contents($location));
            $initial = $this->cleanCache($initial, $location);
        }
        $time = $this->computeExpiryTime($dateTime);
        
        $payload = [$key => ['v' => $value, 't' => $time]];
        $payload = \serialize(\array_merge($payload, $initial));
        \file_put_contents($location, $payload);
    }
    
    public function computeExpiryTime(Carbon $dateTime): ?string
    {
        return empty($dateTime) ? null : $dateTime->format('Y-m-d H:i:s');
    }
    
    /**
     * @param $initial
     * @param $location
     *
     * @return array
     * @throws \Exception
     */
    private function cleanCache($initial, $location): array
    {
        $initial = \array_filter($initial, function ($value) {
            if (!$value['t']) {
                return true;
            }
            $expiry = new \DateTime($value['t']);
            $currentDt = new \DateTime();
            if ($currentDt > $expiry) {
                return false;
            }
            
            return true;
        });
        
        \file_put_contents($location, \serialize($initial));
        
        return $initial;
    }
}
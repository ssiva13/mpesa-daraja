<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Http\Core;

use ArrayAccess;
use Closure;
use Ssiva\MpesaDaraja\Contracts\ConfigurationStore;

class Config implements ConfigurationStore, ArrayAccess
{
    protected array $items = [];
    
    public function __construct($conf = [])
    {
        // Config that comes with the package
        $configFile = approot_path('config/mpesa.php');
        $defaultConfig = [];
        if (\is_file($configFile)) {
            $defaultConfig = require $configFile;
        }
        $defaultConfig = array_merge($defaultConfig, $conf);
        // Config after user edits the config file copied by the system
        $userConfig = __DIR__.'/../../../../../../../config/mpesa.php';
        $custom = [];
        if (\is_file($userConfig)) {
            $custom = require $userConfig;
        }
        if (isset($custom['configs'])){
            $custom = $custom['configs'];
        }
        $this->items = array_merge($defaultConfig, $custom);
    }
    
    public function prepend($key, $value)
    {
        $array = $this->get($key);
        array_unshift($array, $value);
        $this->set($key, $array);
    }
    
    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        $key = str_replace("mpesa.", "", $key);
        $array = $this->items;
        if (!static::accessible($array)) {
            return $this->value($default);
        }
        if (static::exists($array, $key)) {
            return $array[$key];
        }
        if (!str_contains($key, '.')) {
            return $array[$key] ?: $this->value($default);
        }
        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $this->value($default);
            }
        }
        return $array;
    }
    
    public static function accessible($value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
    
    public function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
    
    public static function exists($array, $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }
    
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }
    
    public function all()
    {
        return $this->items;
    }
    
    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }
    
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }
    
    public function offsetUnset($offset): void
    {
        $this->set($offset, null);
    }
}

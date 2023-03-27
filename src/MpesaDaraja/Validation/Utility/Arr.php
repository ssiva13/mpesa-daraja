<?php
/**
 * Date 27/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Utility;
class Arr
{
    
    /**
     * Constant that represents the root of an array
     */
    const PATH_ROOT = '/';
    
    /**
     * @param $paramAttribute
     *
     * @return array
     */
    protected static function getSelectorParts($paramAttribute): array
    {
        $firstOpen = strpos($paramAttribute, '[');
        if ($firstOpen === false) {
            return [$paramAttribute, ''];
        }
        $firstClose = strpos($paramAttribute, ']');
        $container = substr($paramAttribute, 0, $firstOpen);
        $subselector = substr($paramAttribute, $firstOpen + 1, $firstClose - $firstOpen - 1).substr($paramAttribute, $firstClose + 1);
        return [$container, $subselector];
    }
    
    /**
     * Retrieves an element from an array via its path
     *
     * @param array $array
     * @param string $path
     *
     * @return mixed
     */
    public static function getByPath(array $array, string $path = self::PATH_ROOT)
    {
        $path = trim($path);
        if (!$path || $path == self::PATH_ROOT) {
            return $array;
        }
        // fix the path in case it was provided as `[item][subitem]`
        if (strpos($path, '[') === 0) {
            $path = preg_replace('/]/', '', ltrim($path, '['), 1);
        }
        [$container, $subpath] = self::getSelectorParts($path);
        if ($subpath === '') {
            return array_key_exists($container, $array) ? $array[$container] : null;
        }
        return array_key_exists($container, $array) ? self::getByPath($array[$container], $subpath) : null;
    }
    
    /**
     * Set values in the array by selector
     *
     * @param array $array
     * @param string $paramAttribute
     * @param mixed $value
     * @param bool $overwrite true if the $value should overwrite the existing value
     *
     * @return array
     * @example
     * Arr::setBySelector($data, 'email', 'my@domain.com');
     */
    public static function setBySelector(array $array, string $paramAttribute, $value, bool $overwrite = false): array
    {
        // make sure the array is an array in case we got here through a subsequent call
        // so arraySetElementBySelector(array(), 'item[subitem]', 'value');
        // will call arraySetElementBySelector(null, 'subitem', 'value');
        [$container, $subselector] = self::getSelectorParts($paramAttribute);
        if (!$subselector) {
            if ($container !== '*') {
                if ($overwrite === true || !array_key_exists($container, $array)) {
                    $array[$container] = $value;
                }
            }
            return $array;
        }
        // if we have a subselector the $array[$container] must be an array
        if ($container !== '*' && !array_key_exists($container, $array)) {
            $array[$container] = [];
        }
        // we got here through something like *[subitem]
        if ($container === '*') {
            foreach ($array as $key => $v) {
                $array[$key] = self::setBySelector($v, $subselector, $value, $overwrite);
            }
        } else {
            $array[$container] = self::setBySelector($array[$container], $subselector, $value, $overwrite);
        }
        return $array;
    }
    
    /**
     * Get values in the array by selector
     *
     * @param $array
     * @param $paramAttribute
     *
     * @return array
     * @example
     * Arr::getBySelector($data, 'email');
     */
    public static function getBySelector($array, $paramAttribute): array
    {
        if (strpos($paramAttribute, '[*]') === false) {
            return [
                $paramAttribute => self::getByPath($array, $paramAttribute),
            ];
        }
        $result = [];
        [$preffix, $suffix] = explode('[*]', $paramAttribute, 2);
        $base = self::getByPath($array, $preffix);
        if (!is_array($base)) {
            $base = [];
        }
        // we don't have a suffix, the selector was something like path[subpath][*]
        if (!$suffix) {
            foreach ($base as $k => $v) {
                $result["{$preffix}[{$k}]"] = $v;
            }
            // we have a suffix, the selector was something like path[*][item]
        } else {
            foreach ($base as $itemKey => $itemValue) {
                if (is_array($itemValue)) {
                    $result["{$preffix}[{$itemKey}]{$suffix}"] = self::getByPath($itemValue, $suffix);
                }
            }
        }
        return $result;
    }
}

<?php
/**
 * Date 27/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Utility;
class ArrayWrapper
{
    
    protected array $data = [];
    
    public function __construct($data = [])
    {
        if (is_object($data)) {
            if ($data instanceof \ArrayObject) {
                $data = $data->getArrayCopy();
            } elseif (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            }
        }
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data passed to validator is not an array or an ArrayObject');
        }
        $this->data = $data;
    }
    
    public function getItemValue($item)
    {
        return Arr::getByPath($this->data, $item);
    }
    
    public function getItemsBySelector($selector): array
    {
        return Arr::getBySelector($this->data, $selector);
    }
}

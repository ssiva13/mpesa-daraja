<?php
/**
 * Date 29/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

class GreaterThanEqual extends AbstractRule
{
    protected $message = 'This field value is less than the minimum allowed value!';
    protected $labeledMessage = 'The :attribute value is less than the minimum allowed value of :value!';
    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $minValueConfig = $this->options['gte'];
        $minValue = configStore()->get("mpesa.$minValueConfig");
        $this->updateLabeledMessage($minValue);
        $this->success = intval($value) >= $minValue;
        return $this->success;
    }

    private function updateLabeledMessage($minValue){
        $this->labeledMessage = str_replace(':value', $minValue, $this->labeledMessage);
        return $this;
    }
}

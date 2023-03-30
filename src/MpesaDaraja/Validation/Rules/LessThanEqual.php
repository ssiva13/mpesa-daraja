<?php
/**
 * Date 29/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

class LessThanEqual extends AbstractRule
{
    protected $message = 'This field value is greater than the minimum allowed value!';
    protected $labeledMessage = 'The :attribute value is greater than the minimum allowed value of :value!';
    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $maxValueConfig = $this->options['lte'];

        $maxValue = configStore()->get("mpesa.$maxValueConfig");
        $this->updateLabeledMessage($maxValue);
        $this->success = intval($value) <= $maxValue;
        return $this->success;
    }

    private function updateLabeledMessage($maxValue){
        $this->labeledMessage = str_replace(':value', $maxValue, $this->labeledMessage);
        return $this;
    }
}

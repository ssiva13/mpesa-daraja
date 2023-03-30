<?php
/**
 * Date 29/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

class Min extends AbstractRule
{
    protected $message = 'This field value is too short!';
    protected $labeledMessage = 'The :attribute value must be greater or equal to :min characters!';
    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->updateLabeledMessage();
        $minLength = (int) $this->options['min'];
        $this->success = strlen($value) >= $minLength;
        return $this->success;
    }

    private function updateLabeledMessage(){
        $this->labeledMessage = str_replace(':min', $this->options['min'], $this->labeledMessage);
        return $this;
    }
}

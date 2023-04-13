<?php
/**
 * Date 29/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

class Max extends AbstractRule
{
    protected $message = 'This field value is too long!';
    protected $labeledMessage = 'The :attribute value must be less or equal to :max characters!';
    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->updateLabeledMessage();
        $maxLength = (int) $this->options['max'];
        $this->success = strlen($value) <= $maxLength;
        return $this->success;
    }
    
    private function updateLabeledMessage(){
        $this->labeledMessage = str_replace(':max', $this->options['max'], $this->labeledMessage);
        return $this;
    }
}

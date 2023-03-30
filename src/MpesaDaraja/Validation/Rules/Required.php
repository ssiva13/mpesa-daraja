<?php

/**
 * Date 26/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

class Required extends AbstractRule
{
    protected $message = 'This field is required';
    protected $labeledMessage = 'The :attribute is required';
    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = ($value !== null && $value !== '');

        return $this->success;
    }
}

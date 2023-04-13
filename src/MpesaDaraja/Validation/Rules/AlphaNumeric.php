<?php

/**
 * Date 26/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

class AlphaNumeric extends AbstractRule
{
    protected $message = 'This field can contain only be a string';
    protected $labeledMessage = 'The :attribute can contain only be a string';
    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value = $value;
        $this->success = ctype_alnum((string) str_replace(' ', '', $value));
        return $this->success;
    }
}

<?php
/**
 * Date 29/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;
class Integer extends AbstractRule
{
    protected $message = 'This field can contain only numbers!';
    protected $labeledMessage = 'The :attribute can contain only numbers!';
    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = ctype_digit((string) str_replace(' ', '', $value));
        return $this->success;
    }
}

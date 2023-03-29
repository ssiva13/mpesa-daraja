<?php
/**
 * Date 29/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;
class Phone extends AbstractRule
{
    protected $message = 'This field can contain only Kenyan phone numbers!';
    protected $labeledMessage = 'The :attribute can contain only Kenyan phone numbers!';
    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = preg_match('/^(254)[1-9]\d{8}$/', $value);
        return $this->success;
    }
}

<?php
/**
 * Date 29/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;
class Url extends AbstractRule
{
    protected $message = 'This field can contain only a valid URL!';
    protected $labeledMessage = 'The :attribute can contain only a valid URL!';

    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $this->success = (bool) filter_var($value, FILTER_VALIDATE_URL);

        return $this->success;
    }
}

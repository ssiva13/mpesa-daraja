<?php
/**
 * Date 29/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

use \Carbon\Carbon;


class DateTime extends AbstractRule
{
    protected $message = 'Value is not valid!';
    protected $labeledMessage = 'The :attribute should be YmdHis or YYYYMMDDHHMMSS!';

    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $parsedValue = Carbon::createFromFormat('YmdHis', $value)->format('YmdHis');
        $this->success = $parsedValue === $value;
        return $this->success;
    }
}

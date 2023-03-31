<?php

/**
 * Date 26/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

class RequiredIf extends AbstractRule
{
    protected $message = 'This field is required!';
    protected $labeledMessage = 'The :attribute is required if :value is selected!';
    
    protected array $transactions = [
        'CommandID_c2b' => [
            'CustomerPayBillOnline',
        ],
    ];

    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $transactionKey = $this->options['required_if'];
        $this->updateLabeledMessage($transactionKey);
    
        $this->success = ($value !== null && $value !== '');
        // $this->success = !isset($this->transactions[$transactionKey]) || in_array($value, $this->transactions[$transactionKey]);

        return $this->success;
    }
    
    private function updateLabeledMessage($transactionKey): void
    {
        if(isset($this->transactions[$transactionKey])){
            $valueOptions = implode(' or ', $this->transactions[$transactionKey]);
            $this->labeledMessage = str_replace(':value', $valueOptions, $this->labeledMessage);
        }
    }
}

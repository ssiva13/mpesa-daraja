<?php
/**
 * Date 29/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

class Exists extends AbstractRule
{
    protected $message = 'This field can contain only specific values';
    protected $labeledMessage = 'The :attribute can contain only :values !';

    protected array $transactions = [
        'TransactionType_stkpush' => [
            'CustomerPayBillOnline',
            'CustomerBuyGoodsOnline'
        ],
        'CommandID_accountbalance' => [
            'AccountBalance',
        ],
        'CommandID_reversal' => [
            'TransactionReversal',
        ],
        'CommandID_transactionstatus' => [
            'TransactionStatusQuery',
        ],
        'IdentifierType_accountbalance' => [
            2, 4, 1
        ],
        'IdentifierType_transactionstatus' => [
            2, 4, 1
        ],
       'RecieverIdentifierType_accountbalance' => [
            2, 4
        ],
        'CommandID_b2b' => [
            'BusinessPayBill',
        ],
        'CommandID_b2c' => [
            'SalaryPayment',
            'BusinessPayment',
            'PromotionPayment',
        ],
        'SenderIdentifierType_b2b' => [
            2, 4
        ],
        'RecieverIdentifierType_b2b' => [
            2, 4
        ],
        'ResponseType_c2b' => [
            'Canceled',
            'Completed',
        ],
        'CommandID_c2b' => [
            'CustomerPayBillOnline',
            'CustomerBuyGoodsOnline',
        ],

    ];

    /**
     * @inheritDoc
     */
    public function validate($value, $valueIdentifier = null)
    {
        $this->value   = $value;
        $transactionKey = $this->options['exists_in'];
        $this->updateLabeledMessage($transactionKey);
        $this->success = !isset($this->transactions[$transactionKey]) || in_array($value, $this->transactions[$transactionKey]);
        return $this->success;
    }

    private function updateLabeledMessage($transactionKey): void
    {
        if(isset($this->transactions[$transactionKey])){
            $valueOptions = implode(' or ', $this->transactions[$transactionKey]);
            $this->labeledMessage = str_replace(':values', $valueOptions, $this->labeledMessage);
        }
    }
}

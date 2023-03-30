<?php

/**
 * Date 26/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Rules;

use Ssiva\MpesaDaraja\Validation\Utility\ArrayWrapper;

abstract class AbstractRule
{
    // default error message when there is no label attached
    protected $message = 'Value is not valid!';
    // default error message when there is a label attached
    protected $labeledMessage = 'The :attribute is not valid!';
    protected $success = false;
    protected $value;
    protected $options = array();
    private ArrayWrapper $context;

    /**
     * Validates a value
     *
     * @param mixed $value
     * @param null|mixed $valueIdentifier
     *
     * @return mixed
     */
    abstract public function validate($value, $valueIdentifier = null);

    public function getMessage()
    {
        return $this->message;
    }

    public function getLabeledMessage()
    {
        return $this->labeledMessage;
    }

    public function setLabeledMessage($label)
    {
        $this->labeledMessage = str_replace(':attribute', $label, $this->labeledMessage);
    }

    public function setContext($context = null): AbstractRule
    {
        if ($context === null) {
            return $this;
        }
        if (is_array($context)) {
            $context = new ArrayWrapper($context);
        }
        if (! is_object($context) || ! $context instanceof ArrayWrapper) {
            throw new \InvalidArgumentException(
                'Validator context must be either an array or an instance of ArrayWrapper'
            );
        }
        $this->context = $context;

        return $this;
    }
    
    public function setOptions($options)
    {
        foreach ($options as $name => $value) {
            $this->options[$name] = $value;
        }
        return $this;
    }


}

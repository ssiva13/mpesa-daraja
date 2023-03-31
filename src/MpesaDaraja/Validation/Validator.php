<?php
/**
 * Date 26/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation;

use Ssiva\MpesaDaraja\Validation\Rules\AbstractRule;
use Ssiva\MpesaDaraja\Validation\Rules\Required;
use Ssiva\MpesaDaraja\Validation\Utility\ArrayWrapper;
use Ssiva\MpesaDaraja\Validation\Utility\RulesCollection;
use Ssiva\MpesaDaraja\Validation\Utility\ValueValidator;

class Validator implements ValidatorInterface
{
    protected array $messages = [];
    protected array $labeledMessages = [];

    protected array $rules;
    protected RuleFactory $ruleFactory;
    private bool $validated = false;

    /**
     * @var $dataWrapper ArrayWrapper
     */
    protected $dataWrapper;

    public function __construct()
    {
        $this->ruleFactory = new RuleFactory();
    }

    /**
     * @throws \ReflectionException
     */
    public function add($param, $rules)
    {
        $rules = explode('|', $rules);

        foreach ($rules as $rule) {
            $options = [];
            if(str_contains($rule, ':')){
                $rule = strtok($rule, ':');
                $option = strtok( '' );
                $options[$rule] = $option;
            }
            $this->ensureSelectorRulesExist($param);
            call_user_func([$this->rules[$param], 'addRules'], $param, $rule, $options);
        }
    }

    public function remove($param, $rules = true)
    {
        // TODO: Implement remove() method.
    }

    public function setData($data)
    {
        $this->getDataWrapper($data);
        $this->validated = false;
        $this->messages = [];
        $this->labeledMessages = [];
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validate(array $params = []): bool
    {
        if ($params) {
            $this->setData($params);
        }
        // data was already validated, return the results immediately
        if ($this->validated === true) {
            return count($this->messages) === 0 && count($this->labeledMessages) === 0;
        }
        foreach ($this->rules as $param => $valueValidator) {
            foreach ($this->getDataWrapper()->getItemsBySelector($param) as $valueIdentifier => $value) {
                /* @var $valueValidator ValueValidator */
                if (!$valueValidator->validate($value, $valueIdentifier, $this->getDataWrapper())) {
                    foreach ($valueValidator->getMessages() as $message) {
                        $this->addMessage($valueIdentifier, $message);
                    }
                    foreach ($valueValidator->getLabeledMessages() as $message) {
                        $this->addLabeledMessage($valueIdentifier, $message);
                    }

                }
            }
        }
        $this->validated = true;
        return count($this->messages) === 0 && count($this->labeledMessages) === 0;
    }

    private function ensureSelectorRulesExist($param)
    {
        if (!isset($this->rules[$param])) {
            $this->rules[$param] = new ValueValidator($this->ruleFactory,
            // $this->getErroMessagePrototype(),
            // $label
            );
        }
    }

    private function getDataWrapper($data = []): ArrayWrapper
    {
        if (!$this->dataWrapper || $data) {
            $this->dataWrapper = new ArrayWrapper($data);
        }
        return $this->dataWrapper;
    }

    public function addMessage($item, $message = null): static
    {
        if ($message === null || $message === '') {
            return $this;
        }
        if (!array_key_exists($item, $this->messages)) {
            $this->messages[$item] = [];
        }
        $this->messages[$item][] = $message;
        return $this;
    }

    public function addLabeledMessage($item, $message = null): static
    {
        if ($message === null || $message === '') {
            return $this;
        }
        if (!array_key_exists($item, $this->labeledMessages)) {
            $this->labeledMessages[$item] = [];
        }
        $this->labeledMessages[$item][] = $message;
        return $this;
    }

    public function getMessages($item = null)
    {
        if (is_string($item)) {
            return array_key_exists($item, $this->messages) ? $this->messages[$item] : [];
        }
        return $this->messages;
    }

    public function getLabeledMessages($item = null)
    {
        if (is_string($item)) {
            return array_key_exists($item, $this->labeledMessages) ? $this->labeledMessages[$item] : [];
        }
        return $this->labeledMessages;
    }

    public function getRules(){
        return $this->rules;
    }

}

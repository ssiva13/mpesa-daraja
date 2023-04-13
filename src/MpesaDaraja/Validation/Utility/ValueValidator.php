<?php
/**
 * Date 27/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Utility;

use Ssiva\MpesaDaraja\Validation\RuleFactory;
use Ssiva\MpesaDaraja\Validation\Rules\AbstractRule;
use Ssiva\MpesaDaraja\Validation\Rules\Required;
use Ssiva\MpesaDaraja\Validation\Rules\RequiredIf;

class ValueValidator
{
    protected RulesCollection $rules;

    /**
     * @var \Ssiva\MpesaDaraja\Validation\RuleFactory
     */
    private RuleFactory $ruleFactory;
    private array $messages;
    private array $labeledMessages;

    /**
     * @param \Ssiva\MpesaDaraja\Validation\RuleFactory $ruleFactory
     */
    public function __construct(RuleFactory $ruleFactory)
    {
        $this->ruleFactory = $ruleFactory;
        $this->rules = new RulesCollection;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
    public function getLabeledMessages(): array
    {
        return $this->labeledMessages;
    }

    /**
     * @throws \ReflectionException
     */
    public function addRules($param, $rules, $options = []): void
    {
        $rules = explode('|', $rules);
        foreach ($rules as $rule) {
            $ruleKey = "$rule|$param";
            $validator = $this->ruleFactory->createRule($ruleKey, $options);
            $this->addRule($validator);
        }
    }

    private function addRule(AbstractRule $validationRule): void
    {
        $this->rules->attach($validationRule);
    }

    public function validate($value, $valueIdentifier = null, ArrayWrapper $context = null): bool
    {
        $this->messages = [];
        $isRequired = false;
        foreach ($this->rules as $rule) {
            if ($rule instanceof Required) {
                $isRequired = true;
                break;
            }
            if ($rule instanceof RequiredIf) {
                $options = $rule->getOptions();
                $conditionalIdentifier = strtok($options['required_if'], '_');
                $conditionalValue = $context->getItemValue($conditionalIdentifier);
                if($conditionalValue){
                    $isRequired = true;
                }
                break;
            }
        }
        if (!$isRequired && !$value) {
            return true;
        }
        /* @var $rule AbstractRule */
        foreach ($this->rules as $rule) {
            $rule->setContext($context);
            if (!$rule->validate($value, $valueIdentifier)) {
                $this->addLabeledMessage($rule->getLabeledMessage());
                $this->addMessage($rule->getMessage());
            }
            if ($isRequired && count($this->messages)) {
                break;
            }
        }
        return count($this->messages) === 0;
    }

    public function addMessage($message): ValueValidator
    {
        $this->messages[] = $message;
        return $this;
    }
    public function addLabeledMessage($message): ValueValidator
    {
        $this->labeledMessages[] = $message;
        return $this;
    }
}

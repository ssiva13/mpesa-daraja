<?php
/**
 * Date 26/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation;

use ReflectionEnum;
use Ssiva\MpesaDaraja\Validation\Rules\AbstractRule;

class RuleFactory
{
    private $labeledErrorMessages;

    /**
     * @throws \ReflectionException
     */
    public function createRule($ruleName, $options = null, $messageTemplate = null, $label = null)
    {
        $validator = $this->constructValidatorClass($ruleName);
        if ($label = $this->getRuleName($ruleName, false)) {
            /** @var AbstractRule $validator */
            $validator->setLabeledMessage($label);
            $validator->setOptions($options);
        }
        return $validator;
    }

    /**
     * @throws \ReflectionException
     */
    protected function constructValidatorClass($ruleName)
    {
        $ruleUniqueName = $this->getRuleName($ruleName);
        $status = (new ReflectionEnum(__NAMESPACE__.'\\Rules'))->getCase(strtoupper($ruleUniqueName))->getValue();
        $ruleClassName = $this->getRuleClassName($status->rule());
        // try if the validator is the name of a class in the package
        if (class_exists(__NAMESPACE__.'\\Rules\\'.$ruleClassName)) {
            $ruleClassName = __NAMESPACE__.'\\Rules\\'.$ruleClassName;
        }

        if (class_exists($ruleClassName) && is_subclass_of($ruleClassName, __NAMESPACE__.'\\Rules\\AbstractRule')) {
            $validator = new $ruleClassName();
        }
        if (!isset($validator)) {
            throw new \InvalidArgumentException(
                sprintf('Impossible to determine the validator based on the name: %s', (string) $ruleClassName)
            );
        }

        return $validator;
    }

    private function getRuleName($ruleKey, $rule = true): string
    {
        $ruleName = explode('|', $ruleKey);
        $attr = $rule ? current($ruleName) : str_replace('_', ' ', end($ruleName));
        return ucwords(trim($attr));
    }
    private function getRuleClassName($ruleName): string
    {
        $ruleName = str_replace('_', ' ',$ruleName);
        $ruleName = str_replace(' ', '', ucwords($ruleName));
        return trim($ruleName);
    }

    protected function getMessageTemplate($name)
    {
        $noLabelMessage = is_string($name) && isset($this->errorMessages[$name]) ? $this->errorMessages[$name] : null;

        return $noLabelMessage;
    }

}

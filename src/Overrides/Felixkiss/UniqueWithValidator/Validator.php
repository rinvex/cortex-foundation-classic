<?php 

namespace Cortex\Foundation\Overrides\Felixkiss\UniqueWithValidator;

use Felixkiss\UniqueWithValidator\Validator as BaseValidator;
use Cortex\Foundation\Overrides\Felixkiss\UniqueWithValidator\RuleParser;

class Validator extends BaseValidator
{
    public function validateUniqueWith($attribute, $value, $parameters, $validator)
    {
        $ruleParser = new RuleParser($attribute, $value, $parameters, $validator->getData());

        // The presence verifier is responsible for counting rows within this
        // store mechanism which might be a relational database or any other
        // permanent data store like Redis, etc. We will use it to determine
        // uniqueness.
        $presenceVerifier = $validator->getPresenceVerifier();
        if (method_exists($presenceVerifier, 'setConnection')) {
            $presenceVerifier->setConnection($ruleParser->getConnection());
        }
        return $presenceVerifier->getCount(
            $ruleParser->getTable(),
            $ruleParser->getPrimaryField(),
            $ruleParser->getPrimaryValue(),
            $ruleParser->getIgnoreValue(),
            $ruleParser->getIgnoreColumn(),
            $ruleParser->getAdditionalFields()
        ) == 0;
    }
}

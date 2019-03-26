<?php
declare(strict_types=1);

namespace StepTheFkUp\EasyDecision\Middleware;

use StepTheFkUp\EasyDecision\Interfaces\ContextInterface;
use StepTheFkUp\EasyDecision\Interfaces\DecisionInterface;
use StepTheFkUp\EasyDecision\Traits\DealsWithValueInput;

final class ValueMiddleware extends AbstractMiddleware
{
    use DealsWithValueInput;

    /**
     * Make sure children classes handle given context.
     *
     * @param \StepTheFkUp\EasyDecision\Interfaces\ContextInterface $context
     * @param mixed $output
     *
     * @return void
     */
    protected function doHandle(ContextInterface $context, $output): void
    {
        // If value decision, update the input for next middleware
        if ($context->getDecisionType() === DecisionInterface::TYPE_VALUE) {
            $this->addRuleOutput($context, $output);
            $this->updateInput($context, $output);
        }
    }
}

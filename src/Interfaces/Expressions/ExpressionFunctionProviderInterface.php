<?php
declare(strict_types=1);

namespace LoyaltyCorp\EasyDecision\Interfaces\Expressions;

interface ExpressionFunctionProviderInterface
{
    /**
     * Get list of functions.
     *
     * @return mixed[]
     */
    public function getFunctions(): array;
}

\class_alias(
    ExpressionFunctionProviderInterface::class,
    'StepTheFkUp\EasyDecision\Interfaces\Expressions\ExpressionFunctionProviderInterface',
    false
);

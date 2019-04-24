<?php
declare(strict_types=1);

namespace LoyaltyCorp\EasyDecision\Exceptions;

use LoyaltyCorp\EasyDecision\Interfaces\EasyDecisionExceptionInterface;

final class InvalidArgumentException extends \InvalidArgumentException implements EasyDecisionExceptionInterface
{
    // No body needed.
}

\class_alias(
    InvalidArgumentException::class,
    'StepTheFkUp\EasyDecision\Exceptions\InvalidArgumentException',
    false
);

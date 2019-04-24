<?php
declare(strict_types=1);

namespace LoyaltyCorp\EasyDecision\Exceptions;

use LoyaltyCorp\EasyDecision\Interfaces\EasyDecisionExceptionInterface;

final class MissingValueIndexException extends \InvalidArgumentException implements EasyDecisionExceptionInterface
{
    // No body needed.
}

\class_alias(
    MissingValueIndexException::class,
    'StepTheFkUp\EasyDecision\Exceptions\MissingValueIndexException',
    false
);

<?php
declare(strict_types=1);

namespace EonX\EasyDecision\Exception;

use InvalidArgumentException;

final class InvalidExpressionException extends InvalidArgumentException implements EasyDecisionExceptionInterface
{
    // No body needed
}
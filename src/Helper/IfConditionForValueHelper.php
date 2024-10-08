<?php
declare(strict_types=1);

namespace EonX\EasyDecision\Helper;

final class IfConditionForValueHelper
{
    public function __construct(
        private readonly bool $condition,
        private mixed $value,
    ) {
    }

    public function else(mixed $value): self
    {
        if ($this->condition === false) {
            $this->value = $value;
        }

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function then(mixed $value): self
    {
        if ($this->condition === true) {
            $this->value = $value;
        }

        return $this;
    }
}

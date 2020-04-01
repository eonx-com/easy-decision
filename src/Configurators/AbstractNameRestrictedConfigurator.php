<?php

declare(strict_types=1);

namespace EonX\EasyDecision\Configurators;

use EonX\EasyDecision\Interfaces\DecisionInterface;
use EonX\EasyDecision\Interfaces\RestrictedDecisionConfiguratorInterface as RestrictedInterface;

abstract class AbstractNameRestrictedConfigurator extends AbstractConfigurator implements RestrictedInterface
{
    public function supports(DecisionInterface $decision): bool
    {
        return $decision->getName() === $this->getName();
    }

    abstract protected function getName(): string;
}

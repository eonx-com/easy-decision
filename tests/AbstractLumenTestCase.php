<?php

declare(strict_types=1);

namespace EonX\EasyDecision\Tests;

use EonX\EasyDecision\Bridge\Common\Interfaces\DecisionFactoryInterface;
use EonX\EasyDecision\Bridge\Laravel\EasyDecisionServiceProvider;
use Laravel\Lumen\Application;

abstract class AbstractLumenTestCase extends AbstractTestCase
{
    /**
     * @var \Laravel\Lumen\Application
     */
    private $app;

    protected function getApplication(): Application
    {
        if ($this->app !== null) {
            return $this->app;
        }

        $app = new Application(__DIR__);
        $app->register(EasyDecisionServiceProvider::class);

        $app->boot();

        return $this->app = $app;
    }

    protected function getDecisionFactory(): DecisionFactoryInterface
    {
        return $this->getApplication()
            ->make(DecisionFactoryInterface::class);
    }
}

<?php
declare(strict_types=1);

namespace EonX\EasyDecision\Tests\Decisions;

use EonX\EasyDecision\Decisions\UnanimousDecision;
use EonX\EasyDecision\Interfaces\RuleInterface;
use EonX\EasyDecision\Tests\AbstractTestCase;

final class UnanimousDecisionTest extends AbstractTestCase
{
    public function testReturnFalseWhenAtLeastOneFalse(): void
    {
        $decision = (new UnanimousDecision())->addRules([
            $this->createTrueRule('true-1'),
            $this->createTrueRule('true-2'),
            $this->createFalseRule('false-1'),
            $this->createUnsupportedRule('unsupported-1'),
        ]);

        $expected = [
            'true-1' => true,
            'true-2' => true,
            'false-1' => false,
            'unsupported-1' => RuleInterface::OUTPUT_SKIPPED,
        ];

        self::assertFalse($decision->make([]));
        self::assertEquals($expected, $decision->getContext()->getRuleOutputs());
    }

    public function testReturnTrueIfOnlyTrues(): void
    {
        $decision = (new UnanimousDecision())->addRules([
            $this->createTrueRule('true-1'),
            $this->createTrueRule('true-2'),
            $this->createTrueRule('true-3'),
            $this->createUnsupportedRule('unsupported-1'),
        ]);

        $expected = [
            'true-1' => true,
            'true-2' => true,
            'true-3' => true,
            'unsupported-1' => RuleInterface::OUTPUT_UNSUPPORTED,
        ];

        self::assertTrue($decision->make([]));
        self::assertEquals($expected, $decision->getContext()->getRuleOutputs());
    }

    public function testReturnTrueWhenOnlyUnsupported(): void
    {
        $decision = (new UnanimousDecision())->addRules([$this->createUnsupportedRule('unsupported-1')]);

        $expected = [
            'unsupported-1' => RuleInterface::OUTPUT_UNSUPPORTED,
        ];

        self::assertTrue($decision->make([]));
        self::assertEquals($expected, $decision->getContext()->getRuleOutputs());
    }
}

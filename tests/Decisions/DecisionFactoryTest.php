<?php
declare(strict_types=1);

namespace EonX\EasyDecision\Tests\Decisions;

use EonX\EasyDecision\Decisions\DecisionConfig;
use EonX\EasyDecision\Decisions\DecisionFactory;
use EonX\EasyDecision\Decisions\UnanimousDecision;
use EonX\EasyDecision\Exceptions\InvalidDecisionException;
use EonX\EasyDecision\Exceptions\InvalidRuleProviderException;
use EonX\EasyDecision\Expressions\ExpressionLanguageConfig;
use EonX\EasyDecision\Tests\AbstractTestCase;
use EonX\EasyDecision\Tests\Stubs\RuleProviderStub;

final class DecisionFactoryTest extends AbstractTestCase
{
    /**
     * Factory should create expected decision with expected rules.
     *
     * @return void
     */
    public function testCreateDecisionSuccessfully(): void
    {
        $config = new DecisionConfig(
            UnanimousDecision::class,
            'my-decision',
            [new RuleProviderStub()],
            new ExpressionLanguageConfig()
        );

        $decision = (new DecisionFactory($this->getExpressionLanguageFactory()))->create($config);

        $expected = [
            'true-1' => true,
            'value === 1' => true,
            'value < 2' => true
        ];

        self::assertTrue($decision->make(['value' => 1]));
        self::assertEquals(UnanimousDecision::class, $decision->getContext()->getDecisionType());
        self::assertEquals($expected, $decision->getContext()->getRuleOutputs());
    }

    /**
     * Factory should throw exception if instantiated decision does not implement DecisionInterface.
     *
     * @return void
     */
    public function testInvalidDecisionInMappingException(): void
    {
        $this->expectException(InvalidDecisionException::class);

        $config = new DecisionConfig(\stdClass::class, 'my-decision', []);

        (new DecisionFactory($this->getExpressionLanguageFactory()))->create($config);
    }

    /**
     * Factory should throw an exception if invalid rule provider provided.
     *
     * @return void
     */
    public function testInvalidRuleProviderException(): void
    {
        $this->expectException(InvalidRuleProviderException::class);

        $config = new DecisionConfig(UnanimousDecision::class, 'my-decision', [new \stdClass()]);

        (new DecisionFactory($this->getExpressionLanguageFactory()))->create($config);
    }

    /**
     * Factory should throw exception if given decision type isn't in mapping.
     *
     * @return void
     */
    public function testNotInMappingDecisionException(): void
    {
        $this->expectException(InvalidDecisionException::class);

        (new DecisionFactory($this->getExpressionLanguageFactory()))->create(new DecisionConfig(
            '',
            'my-decision',
            []
        ));
    }
}

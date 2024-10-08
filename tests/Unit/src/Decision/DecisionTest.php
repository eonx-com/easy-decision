<?php
declare(strict_types=1);

namespace EonX\EasyDecision\Tests\Unit\Decision;

use EonX\EasyDecision\Decision\ConsensusDecision;
use EonX\EasyDecision\Decision\DecisionInterface;
use EonX\EasyDecision\Decision\ValueDecision;
use EonX\EasyDecision\Provider\ValueExpressionFunctionProvider;
use EonX\EasyDecision\Tests\Stub\Rule\RuleStub;
use EonX\EasyDecision\Tests\Stub\Rule\StopPropagationRuleStub;
use EonX\EasyDecision\Tests\Stub\Rule\WithExtraOutputRuleStub;
use EonX\EasyDecision\Tests\Unit\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class DecisionTest extends AbstractUnitTestCase
{
    /**
     * @see testDecisions
     */
    public static function provideDecisionsData(): iterable
    {
        yield 'No rules' => [
            new ValueDecision(),
            [],
            [
                'value' => 5,
            ],
            5,
            [],
        ];

        yield 'Simple rule' => [
            new ValueDecision(),
            [self::createLanguageRule('add(5)')],
            [
                'value' => 5,
            ],
            10,
            [
                'add(5)' => 10,
            ],
        ];

        yield 'Rules with name and extra' => [
            new ValueDecision(),
            [
                self::createLanguageRule('add(5)'),
                self::createLanguageRule('add(10)', null, 'Add 10'),
                self::createLanguageRule('add(20)', null, null, [
                    'key' => 'value',
                ]),
                self::createLanguageRule('add(30)', null, 'Add 30', [
                    'key1' => 'value1',
                ]),
            ],
            [
                'value' => 5,
            ],
            70,
            [
                'add(5)' => 10,
                'Add 10' => 20,
                'add(20)' => [
                    'output' => 40,
                    'key' => 'value',
                ],
                'Add 30' => [
                    'output' => 70,
                    'key1' => 'value1',
                ],
            ],
        ];

        yield 'Consensus with name and extra' => [
            new ConsensusDecision(),
            [
                new WithExtraOutputRuleStub('Unsupported with extra', false, [
                    'key' => 'value',
                ], false),
                new RuleStub('Only false', false),
                new RuleStub('Only true', true),
                new WithExtraOutputRuleStub('True with extra', true, [
                    'key' => 'value',
                ]),
            ],
            [],
            true,
            [
                'Unsupported with extra' => [
                    'output' => 'unsupported',
                    'key' => 'value',
                ],
                'Only false' => false,
                'Only true' => true,
                'True with extra' => [
                    'output' => true,
                    'key' => 'value',
                ],
            ],
        ];

        yield 'Exit on propagation stopped' => [
            (new ValueDecision())->setExitOnPropagationStopped(),
            [
                self::createLanguageRule('add(5)'),
                new StopPropagationRuleStub('exit-on-propagation-stopped', 10, true),
                self::createLanguageRule('add(10)'),
            ],
            [
                'value' => 5,
            ],
            10,
            [
                'add(5)' => 10,
                'exit-on-propagation-stopped' => 10,
            ],
        ];
    }

    /**
     * @param \EonX\EasyDecision\Rule\RuleInterface[] $rules
     */
    #[DataProvider('provideDecisionsData')]
    public function testDecisions(
        DecisionInterface $decision,
        array $rules,
        array $input,
        mixed $expectedOutput,
        array $expectedRulesOutput,
    ): void {
        $expressionLanguage = $this->createExpressionLanguage();
        $expressionLanguage->addFunctions((new ValueExpressionFunctionProvider())->getFunctions());

        $decision->setExpressionLanguage($expressionLanguage);

        $output = $decision
            ->addRules($rules)
            ->make($input);
        $context = $decision->getContext();

        self::assertEquals($expectedOutput, $output);
        self::assertEquals($decision::class, $context->getDecisionType());
        self::assertEquals($input, $context->getOriginalInput());
        self::assertEquals($expectedRulesOutput, $context->getRuleOutputs());
    }
}

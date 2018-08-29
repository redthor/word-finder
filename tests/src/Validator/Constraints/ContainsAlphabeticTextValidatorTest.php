<?php

declare(strict_types=1);

/**
 * This file is part of the Word Finder package.
 *
 * (c) Douglas Reith
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Validator\Constraints;

use App\Test;
use Symfony\Component\Validator\Context\ExecutionContext;

/**
 * Test class.
 */
class ContainsAlphabeticTextValidatorTest extends Test
{
    public function testContainsAlphabeticTextValidator()
    {
        $this->given($this->newTestedInstance())
            ->then
            ->object($this->testedInstance)
                ->isInstanceOf(ContainsAlphabeticTextValidator::class)
        ;
    }

    public function testPass()
    {
        $alphaOnly = new ContainsAlphabeticText();
        $context = $this->getContext();
        $alpha = 'abc';

        $this->given($this->newTestedInstance())
            ->and($this->testedInstance->initialize($context))
            ->and($this->testedInstance->validate($alpha, $alphaOnly))
            ->then
            ->integer($context->getViolations()->count())
                ->isEqualTo(0);
    }

    public function testFail()
    {
        $alphaOnly = new ContainsAlphabeticText();

        $this
            ->assert('abc1 will fail')
                ->given($this->newTestedInstance())
                ->and($context = $this->getContext())
                ->and($this->testedInstance->initialize($context))
                ->and($this->testedInstance->validate('abc1', $alphaOnly))
                ->then
                ->integer($context->getViolations()->count())
                    ->isEqualTo(1)

            ->assert('👍 will fail')
                ->given($this->newTestedInstance())
                ->and($context = $this->getContext())
                ->and($this->testedInstance->initialize($context))
                ->and($this->testedInstance->validate('👍', $alphaOnly))
                ->then
                ->integer($context->getViolations()->count())
                    ->isEqualTo(1)

            ->assert('这是 will fail')
                ->given($this->newTestedInstance())
                ->and($context = $this->getContext())
                ->and($this->testedInstance->initialize($context))
                ->and($this->testedInstance->validate('这是', $alphaOnly))
                ->then
                ->integer($context->getViolations()->count())
                    ->isEqualTo(1)
        ;
    }

    private function getContext(): ExecutionContext
    {
        $alphaOnly = new ContainsAlphabeticText();
        $translator = $this->newMockInstance(\Symfony\Component\Translation\TranslatorInterface::class);
        $validator = $this->newMockInstance(\Symfony\Component\Validator\Validator\ValidatorInterface::class);
        $context = new ExecutionContext($validator, 'root', $translator);
        $context->setConstraint($alphaOnly);

        return $context;
    }
}

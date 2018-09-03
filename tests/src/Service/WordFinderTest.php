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

namespace App\Service;

use App\Test;

/**
 * Test class.
 */
class WordFinderTest extends Test
{
    public function testWordFinder()
    {
        $this->given($this->newTestedInstance())

            ->then

            ->object($this->testedInstance)
                ->isInstanceOf(WordFinder::class)
        ;
    }

    public function testDelegatePriority()
    {
        /**
         * First inserted will return a value but will be last so should not get
         * a chance.
         *
         * priority = 1, find return = ['5']
         *
         * Second inserted will be a higher priority but not return a value.
         *
         * priority = 9, find return = null
         *
         * Third inserted will be medium priority and will return a value, so we
         * should get that result.
         *
         * priority = 5, find return = ['3']
         */
        $delegate1 = $this->newMockInstance(WordFinderDelegateInterface::class);
        $delegate1->getMockController()->getPriority = function () {
            return 1;
        };
        $delegate1->getMockController()->find = function ($letters) {
            return ['1'];
        };

        $delegate2 = $this->newMockInstance(WordFinderDelegateInterface::class);
        $delegate2->getMockController()->getPriority = function () {
            return 9;
        };
        $delegate2->getMockController()->find = function ($letters) {
            return null;
        };

        $delegate3 = $this->newMockInstance(WordFinderDelegateInterface::class);
        $delegate3->getMockController()->getPriority = function () {
            return 5;
        };
        $delegate3->getMockController()->find = function ($letters) {
            return ['5'];
        };

        $this->given($this->newTestedInstance())
            ->and(
                $this->testedInstance
                    ->addDelegate($delegate1)
                    ->addDelegate($delegate2)
                    ->addDelegate($delegate3)
            )
            ->then

            ->array($this->testedInstance->find('abc'))
                ->string[0]->isEqualTo('5');
    }

    public function testLettersValidationThrowsException()
    {
        $this->given($this->newTestedInstance())
            ->then
            ->exception(function () {
                $this->testedInstance->find('1');
            })
            ->isInstanceOf(\InvalidArgumentException::class)
            ->hasMessage('The string [1] contains an illegal character: it can only contain English letters, no numbers or multibyte characters.')
        ;
    }
}

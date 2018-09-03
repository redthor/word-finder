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

namespace App\Validator;

use App\Test;
use App\Exception\ConsonentsOnlyException;

/**
 * Test class.
 */
class AllowedLettersTest extends Test
{
    public function testPass()
    {
        $alpha = 'abc';

        $this->given($this->newTestedInstance())
            ->then
            ->boolean($this->testedInstance->validate($alpha))
                ->isTrue();
    }

    public function testFail()
    {
        $this
            ->assert('abc1 will fail')
                ->given($this->newTestedInstance())
                ->then
                ->exception(function () {
                    $this->testedInstance->validate('abc1');
                })
                ->isInstanceOf(\InvalidArgumentException::class)
                ->isNotInstanceOf(ConsonentsOnlyException::class)

            ->assert('👍 will fail')
                ->given($this->newTestedInstance())
                ->then
                ->exception(function () {
                    $this->testedInstance->validate('👍');
                })
                ->isInstanceOf(\InvalidArgumentException::class)
                ->isNotInstanceOf(ConsonentsOnlyException::class)

            ->assert('这是 will fail')
                ->given($this->newTestedInstance())
                ->then
                ->exception(function () {
                    $this->testedInstance->validate('这是');
                })
                ->isInstanceOf(\InvalidArgumentException::class)
                ->isNotInstanceOf(ConsonentsOnlyException::class)

            ->assert('tplw will fail because it is all consonents')
                ->given($this->newTestedInstance())
                ->then
                ->exception(function () {
                    $this->testedInstance->validate('tplw');
                })
                ->isInstanceOf(\InvalidArgumentException::class)
                ->isInstanceOf(ConsonentsOnlyException::class)
        ;
    }
}

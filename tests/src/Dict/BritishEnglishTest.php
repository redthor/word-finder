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

namespace App\Dict;

use App\Test;

/**
 * Test class.
 */
class BritishEnglishTest extends Test
{
    public function testBritishEnglish()
    {
        $this->given($this->newTestedInstance())
            ->then

            ->object($this->testedInstance)
                ->isInstanceOf(BritishEnglish::class)
                ->isInstanceOf(\Iterator::class)
                ->isInstanceOf(\Countable::class)
                ->isInstanceOf(\ArrayAccess::class)

            ->integer($this->testedInstance->count())
                ->isEqualTo(72911)

            ->string($this->testedInstance[43887])
                ->isEqualTo('message')

            ->string($this->testedInstance[43583])
                ->isEqualTo('media')

            ->exception(function () {
                $this->testedInstance[200] = 'my own value';
            })
                ->isInstanceOf(\RuntimeException::class)

        ;
    }
}

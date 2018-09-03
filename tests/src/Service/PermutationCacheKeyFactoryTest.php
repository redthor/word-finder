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
class PermutationCacheKeyFactoryTest extends Test
{
    public function testFactoryWillCreateKeys()
    {
        // No matter the order of the letters, the key should be the same
        $letters = 'ewavdddxz';

        $lettersAsArray = \str_split($letters);
        \shuffle($lettersAsArray);
        $shuffled = \implode('', $lettersAsArray);

        $this->given($this->newTestedInstance())
            ->then
                ->string($this->testedInstance->create($letters))
                ->isEqualTo('8805790150090')
                ->isEqualTo($this->testedInstance->create(\strrev($letters)))
                // Try by shuffling as well
                ->isEqualTo($this->testedInstance->create($shuffled))
            ;
    }

    public function testErrorIsThrownIfFactoryCannotCreateKey()
    {
        // Too big
        $letters = 'qqxxzzzxxzzxqjjjzzzzzz';

        $this->given($this->newTestedInstance())
            ->then
            ->exception(function () use ($letters) {
                $this->testedInstance->create($letters);
            })
                ->isInstanceOf(\OutOfBoundsException::class)
            ;
    }

    public function testErrorIsNotThrownForSpecialCase()
    {
        // If the letters are all the same there is a special case
        $letters = 'zzzzzzzzzzzzzzzzzzzzzzzzzzz';

        $this->given($this->newTestedInstance())
            ->then
            ->string($this->testedInstance->create($letters))
                ->isEqualTo('101')
            ;
    }
}

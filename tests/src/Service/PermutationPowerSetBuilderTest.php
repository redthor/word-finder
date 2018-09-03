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
class PermutationPowerSetBuilderTest extends Test
{
    public function testBuilderCreatesPermutations()
    {
        $permutations = [
            'a' => ['a'],
            'ab' => ['a', 'b', 'ab', 'ba'],
            'abc' => ['a', 'b', 'c', 'ba', 'ca', 'ab', 'cb', 'ac', 'bc', 'abc', 'bca', 'cab', 'acb', 'cba', 'bac'],
            'aab' => ['a', 'b', 'aa', 'ba', 'ab', 'aab', 'aba', 'baa'],
            'aabb' => ['a', 'b', 'aa', 'ba', 'ab', 'bb', 'baa', 'aba', 'bba', 'aab', 'bab', 'abb', 'bbaa', 'baba', 'abba', 'baab', 'abab', 'aabb'],
            // Special case that it is assumed that there are no words that are
            // all the same character (apart from single character words). So just
            // the single character is returned
            'aaaaaaaaaa' => ['a'],
        ];

        $this->given($this->newTestedInstance());

        foreach ($permutations as $letters => $expectation) {
            $this->then
                ->array($this->testedInstance->permutations($letters))
                ->hasSize(\count($expectation))
                ->strictlyContainsValues($expectation)
            ;
        }
    }

    public function testBuilderThrowsExceptionWhenOutOfBounds()
    {
        $this->given($this->newTestedInstance())
            ->exception(function () {
                $this->testedInstance->permutations('abcdefghi');
            })
                ->isInstanceOf(\InvalidArgumentException::class)
                ->hasMessage('PermutationPowerSet Builder word size limit [8] reached. [9] passed, reduce by [1]')
            // Eight chars is ok - note, still slow ~ 1sec
            ->array($this->testedInstance->permutations('abcdefgh'))
        ;
    }
}

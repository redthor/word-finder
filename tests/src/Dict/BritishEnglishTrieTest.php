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
class BritishEnglishTrieTest extends Test
{
    public function testBritishEnglishTrie()
    {
        $this->given($this->newTestedInstance())
            ->then

            ->object($this->testedInstance)
                ->isInstanceOf(BritishEnglishTrie::class)

            ->boolean($this->testedInstance->exists('utensil'))
                ->isTrue();
    }
}

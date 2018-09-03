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
class BritishEnglishRadixTrieTest extends Test
{
    public function testBritishEnglishRadixTrie()
    {
        $this->given($this->newTestedInstance())
            ->then

            ->object($this->testedInstance)
                ->isInstanceOf(BritishEnglishRadixTrie::class)

            ->boolean($this->testedInstance->exists('carnivore'))
                ->isTrue();
    }
}

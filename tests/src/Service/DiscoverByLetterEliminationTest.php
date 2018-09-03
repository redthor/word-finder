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
use App\Dict\DictionaryInterface;

/**
 * Test class.
 */
class DiscoverByLetterEliminationTest extends Test
{
    public function testLetterEliminator()
    {
        $dict = $this->newMockInstance(DictionaryInterface::class);
        $dict->getMockController()->getIterator = function () {
            return new \ArrayIterator([
                'jan',
                'brute',
                'jane',
            ]);
        };

        $this->given($this->newTestedInstance($dict))
            ->then
            ->array($this->testedInstance->find('ejan'))
                ->hasSize(2)
                ->strictlyContainsValues(['jan', 'jane'])
        ;
    }
}

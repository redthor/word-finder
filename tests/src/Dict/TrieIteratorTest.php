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
use Tries\RadixTrie;

/**
 * Test class.
 */
class TrieIteratorTest extends Test
{
    public function testIterator()
    {
        $trie = new RadixTrie();
        $trie->add('zany');
        $trie->add('raddish');
        $trie->add('xray');
        $trie->add('editor');

        $this->given($this->newTestedInstance($trie))
            ->then

            ->string($this->testedInstance->current())
                ->isEqualTo('editor')

            ->array($a = \iterator_to_array($this->testedInstance))
                ->hasSize(4)
                ->strictlyContainsValues(['editor', 'raddish', 'xray', 'zany'])
        ;
    }
}

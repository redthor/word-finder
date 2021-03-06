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

use Tries\ITrie;
use Tries\RadixTrie;

/**
 * Class: BritishEnglishRadixTrie.
 */
class BritishEnglishRadixTrie extends AbstractBritishEnglishTrie
{
    /**
     * @return ITrie
     */
    protected static function getTrie(): ITrie
    {
        return new RadixTrie();
    }
}
BritishEnglishRadixTrie::init();

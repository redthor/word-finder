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

/**
 * DictionaryInterface.
 */
interface DictionaryInterface extends \IteratorAggregate
{
    /**
     * @param string $word
     *
     * @return bool
     */
    public function exists(string $word): bool;
}

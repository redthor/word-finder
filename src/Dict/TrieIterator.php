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

/**
 * TrieIterator.
 */
class TrieIterator implements \Iterator
{
    private $trie;

    private $char;

    private $position;

    private $searchResults;

    /**
     * __construct.
     *
     * @param ITrie $trie
     */
    public function __construct(ITrie $trie)
    {
        $this->trie = $trie;

        $this->char = 'a';

        $this->position = 0;

        $this->search();

        $this->valid();
    }

    public function rewind()
    {
        $this->position = 0;

        $this->char = 'a';

        $this->valid();
    }

    public function current()
    {
        return $this->searchResults->key();
    }

    public function key()
    {
        return $this->searchResults->key();
    }

    public function next()
    {
        $this->searchResults->next();

        ++$this->position;

        if (
            !$this->searchResults->valid() &&
            'z' !== $this->char
        ) {
            $this->char = \chr(\ord($this->char) + 1);

            $this->search();
        }
    }

    public function valid(): bool
    {
        while (!$this->searchResults->valid() && 'z' !== $this->char) {
            $this->next();
        }

        return $this->searchResults->valid();
    }

    private function search()
    {
        $this->searchResults = $this->trie->search(
            $this->char
        );
    }
}

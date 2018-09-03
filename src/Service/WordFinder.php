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

use App\Validator\AllowedLetters;
use App\Exception\ConsonentsOnlyException;

/**
 * WordFinder service.
 */
class WordFinder implements WordFinderInterface
{
    private $delegates;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->delegates = new \SplPriorityQueue();
    }

    /**
     * @param WordFinderDelegateInterface $delegate
     *
     * @return self
     */
    public function addDelegate(WordFinderDelegateInterface $delegate): WordFinder
    {
        $this->delegates->insert($delegate, $delegate->getPriority());

        return $this;
    }

    /**
     * @param string $letters
     *
     * @return array|null
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function find(string $letters): ?array
    {
        try {
            (new AllowedLetters())->validate($letters);
        } catch (ConsonentsOnlyException $e) {
            // Return an empty set for just consonents
            return [];
        }

        if (!$this->delegates->count()) {
            throw new \RuntimeException(
                'No WordFinder delegates have been added to the WordFinder service'
            );
        }

        $this->delegates->top();

        while ($this->delegates->valid()) {
            if ($wordList = $this->delegates->current()->find($letters)) {
                return $wordList;
            }

            $this->delegates->next();
        }

        throw new \LogicException(
            'No WordFinder delegates were able to find a result'
        );
    }
}

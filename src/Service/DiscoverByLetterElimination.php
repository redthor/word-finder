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

use Psr\Log\LoggerInterface;
use App\Dict\DictionaryInterface;
use App\Validator\AllowedLetters;
use App\Exception\ConsonentsOnlyException;
use Tries\TrieEntry;

/**
 * DiscoverByLetterElimination service.
 */
class DiscoverByLetterElimination implements WordFinderDelegateInterface
{
    private $dict;

    /**
     * __construct.
     *
     * @param DictionaryInterface $dict
     * @param LoggerInterface     $logger
     */
    public function __construct(
        DictionaryInterface $dict,
        LoggerInterface $logger = null
    ) {
        $this->dict = $dict;

        $logger && $logger->debug(
            \sprintf(
                '[%s] configured with dictionary [%s]',
                __CLASS__,
                \get_class($dict)
            )
        );
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 3;
    }

    /**
     * The method of searching is care of:.
     *
     * @see https://stackoverflow.com/a/25298960/5637853
     *
     * @param string $letters
     *
     * @return array|null - actually, this delegate never returns null, only an
     *                    empty list
     *
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

        $letters = \strtolower($letters);

        $split = \str_split($letters);

        $letterDist = [];

        foreach ($split as $char) {
            $ord = \ord($char);
            isset($letterDist[$ord]) ? $letterDist[$ord]++ : $letterDist[$ord] = 0;
        }

        $matches = [];

        $words = $this->dict->getIterator();

        foreach ($words as $word) {
            if ($word instanceof TrieEntry) {
                $word = $word->value;
            }

            $dictWordSplit = \str_split($word);

            $dictWordDist = [];

            $thisWordMatches = true;

            foreach ($dictWordSplit as $char) {
                $ord = \ord($char);

                isset($dictWordDist[$ord]) ? $dictWordDist[$ord]++ : $dictWordDist[$ord] = 0;

                if (!isset($letterDist[$ord]) || $dictWordDist[$ord] > $letterDist[$ord]) {
                    $thisWordMatches = false;
                    break;
                }
            }

            if ($thisWordMatches) {
                $matches[] = $word;
            }
        }

        return $matches;
    }
}

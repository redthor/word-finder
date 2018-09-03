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

/**
 * PermutationCacheKeyFactory service.
 *
 * This permutation cache key factory has been made with the assumption that the
 * English dictionary does not contain words with length greater than 1 with
 * only repeating characters. E.g. no 'aa' or 'bb' or 'zzz'
 *
 * So if you send in multiple of the same char in a word, you always get back
 * the same key as if you sent in one char of that value.
 *
 * It's not just for letter permutations of any sort.
 */
class PermutationCacheKeyFactory
{
    /**
     * For getting the English letter frequency to dictate smallest Primes,.
     *
     * @see http://pi.math.cornell.edu/~mec/2003-2004/cryptography/subs/frequencies.html
     *
     * @todo This could be calculated from the actual dictionary in use.
     *
     * @var array
     */
    private $primeLetterMapping = [
        'e' => 2,
        't' => 3,
        'a' => 5,
        'o' => 7,
        'i' => 11,
        'n' => 13,
        's' => 17,
        'r' => 19,
        'h' => 23,
        'd' => 29,
        'l' => 31,
        'u' => 37,
        'c' => 41,
        'm' => 43,
        'f' => 47,
        'y' => 53,
        'w' => 59,
        'g' => 61,
        'p' => 67,
        'b' => 71,
        'v' => 73,
        'k' => 79,
        'x' => 83,
        'q' => 89,
        'j' => 97,
        'z' => 101,
    ];

    /**
     * @param string $letters
     *
     * @return string
     */
    public function create(string $letters): string
    {
        $letters = \strtolower($letters);
        $lettersLength = \strlen($letters);

        // Special case, all the letters are the same - use the same key
        $sequence = \implode('', \array_fill(0, $lettersLength, $letters[0]));

        if ($sequence === $letters) {
            return (string) $this->primeLetterMapping[$letters[0]];
        }

        $product = 1;

        for ($i = 0; $i < $lettersLength; ++$i) {
            $product *= $this->primeLetterMapping[$letters[$i]];
        }

        // If PHP_INT_MAX is reached it will be converted to a float, and
        // this could be a problem when cast to a string due to approximation
        // via 'E+18'
        if (\is_float($product)) {
            throw new \OutOfBoundsException(
                \sprintf('Cannot create a cache key from [%s] as it has overflowed the int range', $letters)
            );
        }

        return (string) $product;
    }
}

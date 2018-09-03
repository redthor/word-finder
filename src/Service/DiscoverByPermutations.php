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

/**
 * DiscoverByPermutations service.
 */
class DiscoverByPermutations implements WordFinderDelegateInterface
{
    public const STRING_LENGTH_LIMIT = 6;

    private $permBuilder;

    private $dict;

    /**
     * __construct.
     *
     * @param PermutationPowerSetBuilder $permBuilder
     * @param DictionaryInterface        $dict
     * @param LoggerInterface            $logger
     */
    public function __construct(
        PermutationPowerSetBuilder $permBuilder,
        DictionaryInterface $dict,
        LoggerInterface $logger = null
    ) {
        $this->permBuilder = $permBuilder;
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
        return 5;
    }

    /**
     * @param string $letters
     *
     * @return array|null
     */
    public function find(string $letters): ?array
    {
        if (\strlen($letters) > self::STRING_LENGTH_LIMIT) {
            return null;
        }

        try {
            (new AllowedLetters())->validate($letters);
        } catch (ConsonentsOnlyException $e) {
            // Return an empty set for just consonents
            return [];
        }

        $letters = \strtolower($letters);

        $perms = $this->permBuilder->permutations($letters);

        $valid = [];

        foreach ($perms as $perm) {
            if ($this->dict->exists($perm)) {
                $valid[] = $perm;
            }
        }

        return $valid;
    }
}

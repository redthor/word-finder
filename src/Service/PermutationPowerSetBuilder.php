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

use Psr\SimpleCache\CacheInterface;

/**
 * PermutationPowerSetBuilder service.
 */
class PermutationPowerSetBuilder
{
    private $limit;

    private $cache;

    /**
     * __construct.
     *
     * @param int            $limit
     * @param CacheInterface $cache
     */
    public function __construct(int $limit = 8, CacheInterface $cache = null)
    {
        $this->limit = $limit;

        $this->cache = $cache;
    }

    /**
     * This will create permutations for a string.
     *
     * Note n = strlen($letters) then the permutations is a multiple of n! (due
     * to the permutations of all subsets of the string).
     *
     * So the limit is set to n = 8 which is ~= 90k combinations
     *
     * If all the chars are the same, this has been optimised for the English
     * dictionary where it is assumed that there are no words greater than 1
     * character that have all the same character.
     *
     * So for example permutations('aaaa') => ['a']
     *
     * @param string $letters
     *
     * @return array
     */
    public function permutations(string $letters): array
    {
        $lettersLength = \strlen($letters);

        // Nothing to do for a single letter
        if (1 === $lettersLength) {
            return [$letters];
        }

        // Special case, all the letters are the same - no need
        // to do any work or check the limit. Just send back a single
        // char.
        $sequence = \implode('', \array_fill(0, $lettersLength, $letters[0]));

        if ($sequence === $letters) {
            return [$letters[0]];
        }

        // Not much work for 2 chars
        if (2 === $lettersLength) {
            $rev = \strrev($letters);

            return [$letters[0], $letters[1], $letters, $rev];
        }

        // About to do some processing, check the limit
        if ($lettersLength > $this->limit) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'PermutationPowerSet Builder word size limit [%d] reached. [%d] passed, reduce by [%d]',
                    $this->limit,
                    $lettersLength,
                    $lettersLength - $this->limit
                )
            );
        }

        // Check the cache
        if ($cachedResult = $this->checkCache($letters)) {
            return $cachedResult;
        }

        $permutations = $this->powerPerms(\str_split($letters));

        $results = [];

        foreach ($permutations as $permArray) {
            $result = \implode('', $permArray);
            $results[$result] = true;
        }

        $results = \array_keys($results);

        $this->insertIntoCache($letters, $results);

        return $results;
    }

    /**
     * @param string $letters
     *
     * @return array|null
     */
    private function checkCache(string $letters): ?array
    {
        if (null === $this->cache) {
            return null;
        }

        $key = (new PermutationCacheKeyFactory())->create($letters);

        return $this->cache->get($key);
    }

    /**
     * @param string $letters
     * @param array  $value
     */
    private function insertIntoCache(string $letters, array $value): void
    {
        if (null === $this->cache) {
            return;
        }

        $key = (new PermutationCacheKeyFactory())->create($letters);

        $this->cache->set($key, $value);
    }

    /**
     * The following code is care of dirk.avery@gmail.com.
     *
     * @see http://au2.php.net/manual/en/function.shuffle.php#90615
     */

    /**
     * @param array $arr
     *
     * @return array
     */
    private function powerPerms(array $arr): array
    {
        $powerSet = $this->createPowerSet($arr);

        $result = [];

        foreach ($powerSet as $set) {
            $perms = $this->findPerms($set);
            $result = \array_merge($result, $perms);
        }

        return $result;
    }

    /**
     * @param array $in
     * @param int   $minLength
     *
     * @return array
     */
    private function createPowerSet(array $in, int $minLength = 1): array
    {
        $count = \count($in);
        $members = \pow(2, $count);
        $return = [];
        for ($i = 0; $i < $members; ++$i) {
            $b = \sprintf('%0' . $count . 'b', $i);
            $out = [];
            for ($j = 0; $j < $count; ++$j) {
                if ('1' == $b[$j]) {
                    $out[] = $in[$j];
                }
            }
            if (\count($out) >= $minLength) {
                $return[] = $out;
            }
        }

        return $return;
    }

    /**
     * @param int $int
     *
     * @return int
     */
    private function factorial(int $int): int
    {
        if ($int < 2) {
            return 1;
        }

        for ($f = 2; $int - 1 > 1; $f *= $int--);

        return $f;
    }

    /**
     * findPerm.
     *
     * @param array $arr
     * @param mixed $nth
     *
     * @return array
     */
    private function findPerm(array $arr, $nth = null): array
    {
        if (null === $nth) {
            return $this->findPerms($arr);
        }

        $result = [];
        $length = \count($arr);

        while ($length--) {
            $f = $this->factorial($length);
            $p = \floor($nth / $f);
            $result[] = $arr[$p];
            $this->deleteItemByKey($arr, $p);
            $nth -= $p * $f;
        }

        $result = \array_merge($result, $arr);

        return $result;
    }

    /**
     * @param array $arr
     *
     * @return array
     */
    private function findPerms(array $arr): array
    {
        $p = [];

        for ($i = 0; $i < $this->factorial(\count($arr)); ++$i) {
            $p[] = $this->findPerm($arr, $i);
        }

        return $p;
    }

    /**
     * @param array $array
     * @param mixed $delete_key
     * @param mixed $use_old_keys
     *
     * @return bool
     */
    private function deleteItemByKey(array &$array, $delete_key, $use_old_keys = false): bool
    {
        unset($array[$delete_key]);

        if (!$use_old_keys) {
            $array = \array_values($array);
        }

        return true;
    }
}

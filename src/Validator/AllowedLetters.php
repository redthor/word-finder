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

namespace App\Validator;

use App\Exception\ConsonentsOnlyException;
use Webmozart\Assert\Assert;

/**
 * AllowedLetters class.
 */
class AllowedLetters
{
    /**
     * validate.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @param string $letters
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     * @throws ConsonentsOnlyException
     */
    public function validate(string $letters): bool
    {
        Assert::notEmpty($letters);

        if (!\ctype_alpha($letters)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'The string [%s] contains an illegal character: it can only contain English letters, no numbers or multibyte characters.',
                    $letters
                )
            );
        }

        $vowels = 'aeiouy';

        $letters = \strtolower($letters);

        if (\strlen($letters) > 1 && false === \strpbrk($letters, $vowels)) {
            throw new ConsonentsOnlyException(
                \sprintf(
                    'The string [%s] is greater than 1 char and has no vowels',
                    $letters
                )
            );
        }

        return true;
    }
}

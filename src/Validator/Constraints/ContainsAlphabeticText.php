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

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * ContainsAlphabeticText class.
 */
class ContainsAlphabeticText extends Constraint
{
    public $message = 'The string "{{ string }}" contains an illegal character: it can only contain English letters, no numbers or multibyte characters.';
}

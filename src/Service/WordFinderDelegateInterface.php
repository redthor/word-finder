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
 * WordFinderDelegateInterface.
 */
interface WordFinderDelegateInterface extends WordFinderInterface
{
    /**
     * @return int
     */
    public function getPriority(): int;
}

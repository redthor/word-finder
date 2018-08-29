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

namespace App;

use atoum;

/**
 * Base Test class.
 */
class Test extends atoum
{
    /**
     * getTestedClassName.
     *
     * @return string
     */
    public function getTestedClassName(): string
    {
        /** @var string $testClass FQN e.g. PI\Compliance\Domain\TaskTest */
        $testClass = \get_class($this);

        if ('Test' !== \substr($testClass, -4)) {
            throw new \RuntimeException(
                \sprintf('Test class name is expected to end with "Test". FQN is [%s].', $testClass)
            );
        }

        return \substr($testClass, 0, -4);
    }
}

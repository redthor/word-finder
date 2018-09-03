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

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Service\WordFinderInterface;
use App\Test;

/**
 * Test class.
 */
class WordFinderControllerTest extends Test
{
    public function testWordFinderController()
    {
        $wordFinder = $this->newMockInstance(WordFinderInterface::class);

        $wordFinder->getMockController()->find = function (string $letters) {
            return ['a', 'b', 'ab', 'ba'];
        };

        $letters = 'ab';

        $this->given($this->newTestedInstance())

            ->then

            ->object($this->testedInstance)
                ->isInstanceOf(WordFinderController::class)

            ->object($response = $this->testedInstance->wordFinder($letters, $wordFinder))
                ->isInstanceOf(Response::class)

            ->string($response->getContent())
                ->isEqualTo('["a", "b", "ab", "ba"]')
        ;
    }
}

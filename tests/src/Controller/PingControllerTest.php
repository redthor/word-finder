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
use App\Test;

/**
 * Test class.
 */
class PingControllerTest extends Test
{
    public function testPingController()
    {
        $this->given($this->newTestedInstance())

            ->then

            ->string(\get_class($this->testedInstance))
                ->isEqualTo(PingController::class)

            ->object($response = $this->testedInstance->ping())
                ->isInstanceOf(Response::class);
    }
}

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
use Symfony\Component\Routing\Annotation\Route;

/**
 * PingController class.
 */
class PingController
{
    /**
     * @Route("/ping", name="wf_ping")
     *
     * @return Response
     */
    public function ping(): Response
    {
        return new Response('OK 👍');
    }
}

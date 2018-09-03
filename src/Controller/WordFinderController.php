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
use App\Service\WordFinderInterface;

/**
 * WordFinderController class.
 */
class WordFinderController
{
    /**
     * @Route(
     *     "/wordfinder/{letters}",
     *     name="wf_wordfinder"
     * )
     *
     * @return Response
     */
    public function wordFinder(string $letters, WordFinderInterface $wordFinder): Response
    {
        $words = $wordFinder->find($letters);

        return new Response(
            \sprintf(
                '["%s"]',
                \implode('", "', $words)
            )
        );
    }
}

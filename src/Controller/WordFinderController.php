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
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as WfAssert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function wordFinder(string $letters, ValidatorInterface $validator): Response
    {
        $notBlank = new Assert\NotBlank();
        $minLength = new Assert\Length(['min' => 1]);
        $alphaOnly = new WfAssert\ContainsAlphabeticText();

        $validator->validate($letters, [$notBlank, $minLength, $alphaOnly]);

        return new Response($letters);
    }
}

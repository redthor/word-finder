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

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class: RunAllWordFinders.
 *
 * @see Command
 */
class RunAllWordFinders extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:run-all-word-finders')
            ->setDescription('Find words given a set of characters using all combinations.')
            ->addArgument('characters', InputArgument::REQUIRED, 'Input characters to try and find dictionary words using some or all of the characters.')
            ->setHelp(<<<EOT
<info>app:run-all-word-finders <characters></info>

EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $letters = $input->getArgument('characters');

        $command = $this->getApplication()->find('app:find-words');

        $args = [
            'command' => 'app:find-words',
            'characters' => $letters,
        ];

        foreach (['array', 'trie', 'radix'] as $dictType) {
            $args['--dict-type'] = $dictType;

            $findWordsInput = new ArrayInput($args);

            $command->run($findWordsInput, $output);
        }

        $limitTests = \strlen($letters) > 7;

        if ($limitTests) {
            $output->writeln(
                '<comment>Cannot run all tests as too many chars passed</comment>'
            );

            return;
        }

        $output->writeln([
            '',
            '<info>Forcing dictionary letter search</info>',
        ]);

        $args['--force-dict-search'] = true;

        foreach (['array', 'trie', 'radix'] as $dictType) {
            $args['--dict-type'] = $dictType;

            $findWordsInput = new ArrayInput($args);

            $command->run($findWordsInput, $output);
        }
    }
}

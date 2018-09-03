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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\WordFinderInterface;
use App\Dict\BritishEnglish;
use App\Dict\BritishEnglishTrie;
use App\Dict\BritishEnglishRadixTrie;
use App\Service\DiscoverByPermutations;
use App\Service\DiscoverByLetterElimination;
use App\Service\PermutationPowerSetBuilder;

/**
 * Class: FindWords.
 *
 * @see Command
 */
class FindWords extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:find-words')
            ->setDescription('Find words given a set of characters.')
            ->addArgument('characters', InputArgument::REQUIRED, 'Input characters to try and find dictionary words using some or all of the characters.')
            ->addOption(
                'dict-type',
                't',
                InputOption::VALUE_REQUIRED,
                'Dictionary type: array | trie | radix',
                'array'
            )
            ->addOption(
                'force-dict-search',
                'f',
                InputOption::VALUE_NONE,
                'For characters < 8 force a full dictionary search',
                null
            )
            ->setHelp(<<<EOT
<info>app:find-words <characters> --dictionary-type=array --force-dictionary-search</info>

    <comment><characters></comment>        - Required characters to find words from.
    <comment>--dict-type</comment>         - Optional. Defaults to 'array'. Allowed types are: array | trie | radix
    <comment>--force-dict-search</comment> - Optional. Full dictionary search will always occur when characters length is greater than 7. You can force it to happen when < 7 with this flag.

EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $letters = $input->getArgument('characters');

        $wordFinder = $this->getWordFinder($input, $output, $letters);

        $startTime = \microtime(true);

        $words = $wordFinder->find($letters);

        $endTime = \microtime(true);

        $callTime = $endTime - $startTime;

        $output->writeln([
            \sprintf('Words: %s', \implode(', ', $words)),
            \sprintf('Took %.4fs', $callTime),
        ]);
    }

    /**
     * @param InputInterface $input
     * @param string         $letters
     *
     * @return WordFinderInterface
     */
    private function getWordFinder(InputInterface $input, OutputInterface $output, string $letters): WordFinderInterface
    {
        $dictType = $input->getOption('dict-type');

        $dict = null;

        switch ($dictType) {
            case 'array':
                $dict = new BritishEnglish();
                break;

            case 'trie':
                $dict = new BritishEnglishTrie();
                break;

            case 'radix':
                $dict = new BritishEnglishRadixTrie();
                break;

            default:
                throw new \InvalidArgumentException(
                    \sprintf('Dictionary type [%s] unrecognised. Allowed types are [%s]', $dictType, \implode(', ', ['array', 'trie', 'radix']))
                );
        }

        $output->writeln(\sprintf('Using <comment>%s-type</comment> dictionary', $dictType));

        $forceDictSearch = $input->getOption('force-dict-search') || \strlen($letters) > 7;

        if ($forceDictSearch) {
            $output->writeln(
                \sprintf('Using <comment>[%s]</comment> find words strategy', DiscoverByLetterElimination::class)
            );

            return new DiscoverByLetterElimination(
                $dict
            );
        }

        $output->writeln(
            \sprintf('Using <comment>[%s]</comment> find words strategy', DiscoverByPermutations::class)
        );

        return new DiscoverByPermutations(
            new PermutationPowerSetBuilder(),
            $dict
        );
    }
}

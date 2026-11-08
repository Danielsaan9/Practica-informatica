<?php

namespace Glpi\FuzzyMatcher;

/**
 * Default strategy.
 * Allow partial matches on string thanks to a zero deletion cost.
 * See tests for more precise examples.
 */
final class PartialMatchStrategy implements FuzzyMatcherStrategyInterface
{
    public function tryToMatchUsingStrContains(): bool
    {
        return true;
    }

    public function minimumFilterLenghtForFuzzySearch(): int
    {
        return 3;
    }

    public function insertionCost(): int
    {
        return 1;
    }

    public function replacementCost(): int
    {
        return 1;
    }

    public function deletionCost(): int
    {
        return 0;
    }

    public function maxCostForSuccess(?int $word_length = 0): int
    {
        // Allow up to 10% of the word length as cost
        return (int) ceil($word_length * 0.1);
    }
}

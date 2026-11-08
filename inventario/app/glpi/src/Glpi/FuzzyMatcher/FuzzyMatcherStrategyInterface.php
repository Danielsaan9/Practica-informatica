<?php

namespace Glpi\FuzzyMatcher;

interface FuzzyMatcherStrategyInterface
{
    /**
     * If true, the matcher will run a simple str_contains check before the
     * actual fuzzy matching.
     * Might be useful if you also enable the minimumFilterLenghtForFuzzySearch.
     */
    public function tryToMatchUsingStrContains(): bool;

    /**
     * If above 0, will disable fuzzy matching if the filter is below the
     * specified length.
     * Useful to avoid having too many irrelevant match on stategies with a low
     * deletion cost.
     */
    public function minimumFilterLenghtForFuzzySearch(): int;

    /**
     * @see https://www.php.net/manual/en/function.levenshtein.php
     */
    public function insertionCost(): int;

    /**
     * @see https://www.php.net/manual/en/function.levenshtein.php
     */
    public function replacementCost(): int;

    /**
     * @see https://www.php.net/manual/en/function.levenshtein.php
     */
    public function deletionCost(): int;

    /**
     * @see https://www.php.net/manual/en/function.levenshtein.php
     */
    public function maxCostForSuccess(?int $string_length): int;
}

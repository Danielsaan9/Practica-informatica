<?php

namespace Glpi\Form\ServiceCatalog\SortStrategy;

enum SortStrategyEnum: string
{
    case ALPHABETICAL         = 'alphabetical';
    case REVERSE_ALPHABETICAL = 'reverse_alphabetical';
    case POPULARITY           = 'popularity';

    /**
     * Create a new instance of the strategy
     */
    public function getStrategy(): SortStrategyInterface
    {
        return match ($this) {
            self::ALPHABETICAL => new AlphabeticalSort(),
            self::REVERSE_ALPHABETICAL => new ReverseAlphabeticalSort(),
            self::POPULARITY => new PopularitySort(),
        };
    }

    /**
     * Get the default strategy
     */
    public static function getDefault(): self
    {
        return self::POPULARITY;
    }

    /**
     * Get all available sort strategies
     *
     * @return array<string, SortStrategyInterface>
     */
    public static function getAvailableStrategies(): array
    {
        $strategies = [];

        foreach (SortStrategyEnum::cases() as $case) {
            $strategies[$case->value] = $case->getStrategy();
        }

        return $strategies;
    }
}

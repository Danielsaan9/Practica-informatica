<?php

namespace Glpi\Form\Destination;

use BackedEnum;

/**
 * Shared strategies deserialization for configs that hold multiple strategies.
 */
trait DeserializeStrategiesTrait
{
    /**
     * @template T of BackedEnum
     *
     * @param array<string, mixed> $data
     * @param T                    $default
     *
     * @return non-empty-array<T>
     */
    protected static function deserializeStrategies(array $data, BackedEnum $default): array
    {
        $strategy_class = $default::class;
        $strategies = array_values(array_filter(array_map(
            fn($strategy) => is_scalar($strategy)
                ? $strategy_class::tryFrom((string) $strategy)
                : null,
            $data[static::getStrategiesInputName()] ?? []
        )));

        return $strategies ?: [$default];
    }
}

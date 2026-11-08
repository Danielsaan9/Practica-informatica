<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Destination\ConfigFieldWithStrategiesInterface;
use Override;

final class ValidationFieldConfig implements
    JsonFieldInterface,
    ConfigFieldWithStrategiesInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const STRATEGIES = 'strategies';
    public const STRATEGY_CONFIGS = 'strategy_configs';

    /**
     * @param array<ValidationFieldStrategyConfig> $strategy_configs
     */
    public function __construct(
        private array $strategy_configs = []
    ) {
        // Ensure we have at least one strategy
        if ($this->strategy_configs === []) {
            $this->strategy_configs[] = new ValidationFieldStrategyConfig(
                ValidationFieldStrategy::NO_VALIDATION
            );
        }
    }

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $strategy_configs = [];
        foreach ($data[self::STRATEGY_CONFIGS] as $config_data) {
            $strategy_configs[] = ValidationFieldStrategyConfig::jsonDeserialize($config_data);
        }
        return new self($strategy_configs);
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::STRATEGY_CONFIGS => array_map(
                fn(ValidationFieldStrategyConfig $config) => $config->jsonSerialize(),
                $this->strategy_configs
            ),
        ];
    }

    #[Override]
    public static function getStrategiesInputName(): string
    {
        return self::STRATEGIES;
    }

    /**
     * @return array<ValidationFieldStrategy>
     */
    public function getStrategies(): array
    {
        return array_map(
            fn(ValidationFieldStrategyConfig $config) => $config->getStrategy(),
            $this->strategy_configs
        );
    }

    /**
     * @return array<ValidationFieldStrategyConfig>
     */
    public function getStrategyConfigs(): array
    {
        return $this->strategy_configs;
    }

    public function getStrategyConfigByIndex(int $index): ?ValidationFieldStrategyConfig
    {
        return $this->strategy_configs[$index] ?? null;
    }
}

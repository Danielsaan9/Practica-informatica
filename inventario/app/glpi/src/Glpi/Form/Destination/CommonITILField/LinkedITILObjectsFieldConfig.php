<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Destination\HasFieldWithDestinationId;
use Glpi\Form\Destination\HasFieldWithQuestionId;
use Override;

#[HasFieldWithQuestionId(
    LinkedITILObjectsFieldStrategyConfig::SPECIFIC_QUESTION_IDS,
    is_array: true,
    list_of_strategies_field: self::STRATEGY_CONFIGS,
)]
#[HasFieldWithDestinationId(
    LinkedITILObjectsFieldStrategyConfig::SPECIFIC_DESTINATION_IDS,
    is_array: true,
    list_of_strategies_field: self::STRATEGY_CONFIGS,
)]
final class LinkedITILObjectsFieldConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const STRATEGY_CONFIGS = 'strategy_configs';

    /**
     * @param array<LinkedITILObjectsFieldStrategyConfig> $strategy_configs
     */
    public function __construct(
        private array $strategy_configs = []
    ) {
        // Ensure we have at least one config
        if ($this->strategy_configs === []) {
            $this->strategy_configs[] = new LinkedITILObjectsFieldStrategyConfig();
        }
    }

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $strategy_configs = [];
        foreach ($data[self::STRATEGY_CONFIGS] as $config_data) {
            $strategy_configs[] = LinkedITILObjectsFieldStrategyConfig::jsonDeserialize($config_data);
        }
        return new self($strategy_configs);
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::STRATEGY_CONFIGS => array_map(
                fn(LinkedITILObjectsFieldStrategyConfig $config) => $config->jsonSerialize(),
                $this->strategy_configs
            ),
        ];
    }

    /**
     * @return array<LinkedITILObjectsFieldStrategy>
     */
    public function getStrategies(): array
    {
        return array_map(
            fn(LinkedITILObjectsFieldStrategyConfig $config) => $config->getStrategy(),
            $this->strategy_configs
        );
    }

    /**
     * @return array<LinkedITILObjectsFieldStrategyConfig>
     */
    public function getStrategyConfigs(): array
    {
        return $this->strategy_configs;
    }

    public function getStrategyConfigByIndex(int $index): ?LinkedITILObjectsFieldStrategyConfig
    {
        return $this->strategy_configs[$index] ?? null;
    }
}

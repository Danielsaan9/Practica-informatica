<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Destination\ConfigFieldWithStrategiesInterface;
use Override;

final class ITILTaskFieldConfig implements
    JsonFieldInterface,
    ConfigFieldWithStrategiesInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const STRATEGY = 'strategy';
    public const TASKTEMPLATE_IDS = 'tasktemplate_ids';

    public function __construct(
        private ITILTaskFieldStrategy $strategy,
        private ?array $specific_itiltasktemplates_ids = null,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $strategy = ITILTaskFieldStrategy::tryFrom($data[self::STRATEGY] ?? "");
        if ($strategy === null) {
            $strategy = ITILTaskFieldStrategy::NO_TASK;
        }

        return new self(
            strategy: $strategy,
            specific_itiltasktemplates_ids: $data[self::TASKTEMPLATE_IDS] ?? [],
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::STRATEGY => $this->strategy->value,
            self::TASKTEMPLATE_IDS => $this->specific_itiltasktemplates_ids,
        ];
    }

    #[Override]
    public static function getStrategiesInputName(): string
    {
        return self::STRATEGY;
    }

    /**
     * @return array<ITILTaskFieldStrategy>
     */
    public function getStrategies(): array
    {
        return [$this->strategy];
    }

    public function getSpecificTaskTemplatesIds(): ?array
    {
        return $this->specific_itiltasktemplates_ids;
    }
}

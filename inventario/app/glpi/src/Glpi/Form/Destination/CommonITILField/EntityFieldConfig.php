<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Destination\ConfigFieldWithStrategiesInterface;
use Glpi\Form\Destination\HasFieldWithQuestionId;
use Override;

#[HasFieldWithQuestionId(self::SPECIFIC_QUESTION_ID)]
final class EntityFieldConfig implements
    JsonFieldInterface,
    ConfigFieldWithStrategiesInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const STRATEGY = 'strategy';
    public const SPECIFIC_QUESTION_ID = 'specific_question_id';
    public const SPECIFIC_ENTITY_ID = 'specific_entity_id';

    public function __construct(
        private EntityFieldStrategy $strategy,
        private ?int $specific_question_id = null,
        private ?int $specific_entity_id = null,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $strategy = EntityFieldStrategy::tryFrom($data[self::STRATEGY] ?? "");
        if ($strategy === null) {
            $strategy = EntityFieldStrategy::LAST_VALID_ANSWER;
        }

        return new self(
            strategy: $strategy,
            specific_question_id: $data[self::SPECIFIC_QUESTION_ID] ?? null,
            specific_entity_id: $data[self::SPECIFIC_ENTITY_ID] ?? null,
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::STRATEGY => $this->strategy->value,
            self::SPECIFIC_QUESTION_ID => $this->specific_question_id,
            self::SPECIFIC_ENTITY_ID => $this->specific_entity_id,
        ];
    }

    #[Override]
    public static function getStrategiesInputName(): string
    {
        return self::STRATEGY;
    }

    public function getStrategy(): EntityFieldStrategy
    {
        return $this->strategy;
    }

    /**
     * @return array<EntityFieldStrategy>
     */
    public function getStrategies(): array
    {
        // TODO: why does this field seems to support multiple stategies?
        // It doesn't make sense IMO, can you take a look @ccailly?
        return [$this->strategy];
    }

    public function getSpecificQuestionId(): ?int
    {
        return $this->specific_question_id;
    }

    public function getSpecificEntityId(): ?int
    {
        return $this->specific_entity_id;
    }
}

<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Destination\ConfigFieldWithStrategiesInterface;
use Glpi\Form\Destination\HasFieldWithQuestionId;
use Override;

#[HasFieldWithQuestionId(self::SPECIFIC_QUESTION_ID)]
final class UrgencyFieldConfig implements
    JsonFieldInterface,
    ConfigFieldWithStrategiesInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const STRATEGY = 'strategy';
    public const SPECIFIC_QUESTION_ID = 'specific_question_id';
    public const SPECIFIC_URGENCY_VALUE = 'specific_urgency_value';

    public function __construct(
        private UrgencyFieldStrategy $strategy,
        private ?int $specific_question_id = null,
        private ?int $specific_urgency_value = null,
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $strategy = UrgencyFieldStrategy::tryFrom($data[self::STRATEGY] ?? "");
        if ($strategy === null) {
            $strategy = UrgencyFieldStrategy::LAST_VALID_ANSWER;
        }

        return new self(
            strategy: $strategy,
            specific_question_id: $data[self::SPECIFIC_QUESTION_ID] ?? null,
            specific_urgency_value: $data[self::SPECIFIC_URGENCY_VALUE] ?? null,
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::STRATEGY => $this->strategy->value,
            self::SPECIFIC_QUESTION_ID => $this->specific_question_id,
            self::SPECIFIC_URGENCY_VALUE => $this->specific_urgency_value,
        ];
    }

    #[Override]
    public static function getStrategiesInputName(): string
    {
        return self::STRATEGY;
    }

    /**
     * @return array<UrgencyFieldStrategy>
     */
    #[Override]
    public function getStrategies(): array
    {
        return [$this->strategy];
    }

    public function getSpecificUrgency(): ?int
    {
        return $this->specific_urgency_value;
    }

    public function getSpecificQuestionId(): ?int
    {
        return $this->specific_question_id;
    }
}

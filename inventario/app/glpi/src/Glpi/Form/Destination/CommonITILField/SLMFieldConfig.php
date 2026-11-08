<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Destination\ConfigFieldWithStrategiesInterface;
use Override;

abstract class SLMFieldConfig implements
    JsonFieldInterface,
    ConfigFieldWithStrategiesInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const STRATEGY        = 'strategy';
    public const SLM_ID          = 'slm_id';
    public const QUESTION_ID     = 'question_id';
    public const TIME_OFFSET     = 'time_offset';
    public const TIME_DEFINITION = 'time_definition';

    public function __construct(
        private SLMFieldStrategy $strategy,
        private ?int $specific_slm_id    = null,
        private ?int $question_id        = null,
        private ?int $time_offset        = null,
        private ?string $time_definition = null,
    ) {}

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::STRATEGY        => $this->strategy->value,
            self::SLM_ID          => $this->specific_slm_id,
            self::QUESTION_ID     => $this->question_id,
            self::TIME_OFFSET     => $this->time_offset,
            self::TIME_DEFINITION => $this->time_definition,
        ];
    }

    #[Override]
    public static function getStrategiesInputName(): string
    {
        return self::STRATEGY;
    }

    /**
     * @return array<SLMFieldStrategy>
     */
    public function getStrategies(): array
    {
        return [$this->strategy];
    }

    public function getSpecificSLMID(): ?int
    {
        return $this->specific_slm_id;
    }

    public function getQuestionId(): ?int
    {
        return $this->question_id;
    }

    public function getTimeOffset(): ?int
    {
        return $this->time_offset;
    }

    public function getTimeDefinition(): ?string
    {
        return $this->time_definition;
    }
}

<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Override;

/**
 * Configuration for a single validation strategy
 */
final class ValidationFieldStrategyConfig implements JsonFieldInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const STRATEGY = 'strategy';
    public const SPECIFIC_VALIDATION_TEMPLATE_IDS = 'specific_validationtemplates_ids';
    public const SPECIFIC_QUESTION_IDS = 'specific_question_ids';
    public const SPECIFIC_ACTORS = 'specific_actors';
    public const SPECIFIC_VALIDATION_STEP_ID = 'specific_validation_step_id';

    /**
     * @param array<int> $specific_validationtemplate_ids
     * @param array<int> $specific_question_ids
     * @param array<string, int[]> $specific_actors
     */
    public function __construct(
        private ValidationFieldStrategy $strategy,
        private array $specific_validationtemplate_ids = [],
        private array $specific_question_ids = [],
        private array $specific_actors = [],
        private ?int $specific_validation_step_id = null
    ) {}

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $strategy = ValidationFieldStrategy::tryFrom($data[self::STRATEGY] ?? "");
        if ($strategy === null) {
            $strategy = ValidationFieldStrategy::NO_VALIDATION;
        }

        return new self(
            strategy: $strategy,
            specific_validationtemplate_ids: $data[self::SPECIFIC_VALIDATION_TEMPLATE_IDS] ?? [],
            specific_question_ids: $data[self::SPECIFIC_QUESTION_IDS] ?? [],
            specific_actors: $data[self::SPECIFIC_ACTORS] ?? [],
            specific_validation_step_id: $data[self::SPECIFIC_VALIDATION_STEP_ID] ?? null
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::STRATEGY => $this->strategy->value,
            self::SPECIFIC_VALIDATION_TEMPLATE_IDS => $this->specific_validationtemplate_ids,
            self::SPECIFIC_QUESTION_IDS => $this->specific_question_ids,
            self::SPECIFIC_ACTORS => $this->specific_actors,
            self::SPECIFIC_VALIDATION_STEP_ID => $this->specific_validation_step_id,
        ];
    }

    public function getStrategy(): ValidationFieldStrategy
    {
        return $this->strategy;
    }

    public function getSpecificValidationTemplateIds(): array
    {
        return $this->specific_validationtemplate_ids;
    }

    public function getSpecificQuestionIds(): array
    {
        return $this->specific_question_ids;
    }

    public function getSpecificActors(): array
    {
        return $this->specific_actors;
    }

    public function getSpecificValidationStepId(): ?int
    {
        return $this->specific_validation_step_id;
    }
}

<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ValueOperator;
use Glpi\Form\QuestionType\QuestionTypeUserDevice;
use Glpi\Form\QuestionType\QuestionTypeUserDevicesConfig;
use Override;

/**
 * Allow text comparison on items using contains operator.
 */
final class UserDevicesAsTextConditionHandler implements ConditionHandlerInterface
{
    public function __construct(
        private QuestionTypeUserDevicesConfig $question_config
    ) {}

    #[Override]
    public function getSupportedValueOperators(): array
    {
        return [
            ValueOperator::CONTAINS,
            ValueOperator::NOT_CONTAINS,
        ];
    }

    #[Override]
    public function getTemplate(): string
    {
        return '/pages/admin/form/condition_handler_templates/input.html.twig';
    }

    #[Override]
    public function getTemplateParameters(ConditionData $condition): array
    {
        return [];
    }

    #[Override]
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool {
        if (!$this->question_config->isMultipleDevices() && is_string($a)) {
            $a = [$a];
        }

        if (!is_array($a)) {
            return false;
        }

        // Get valid item names from the raw answer
        $a = (new QuestionTypeUserDevice())->transformConditionValueForComparisons($a, $this->question_config);

        // Normalize values
        $a = array_map(fn(string $item) => strtolower(strval($item)), $a);
        $b = strtolower(strval($b));

        return match ($operator) {
            ValueOperator::CONTAINS     => array_reduce(
                $a,
                fn(bool $carry, string $item) => $carry || str_contains($item, $b),
                false
            ),
            ValueOperator::NOT_CONTAINS => !array_reduce(
                $a,
                fn(bool $carry, string $item) => $carry || str_contains($item, $b),
                false
            ),

            // Unsupported operators
            default => false,
        };
    }
}

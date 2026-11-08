<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ValueOperator;
use Override;

/**
 * Allow text comparison on items using contains operator.
 */
final class ItemAsTextConditionHandler implements ConditionHandlerInterface
{
    public function __construct(
        private string $itemtype,
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
        // $a is the submitted answer
        if (!is_array($a) || !isset($a['items_ids'])) {
            return false;
        }

        $names = [];
        foreach ((array) $a['items_ids'] as $items_id) {
            $item = $this->itemtype::getById((int) $items_id);
            if ($item) {
                $names[] = $item->getName(['complete' => true]);
            }
        }

        if ($names === []) {
            return false;
        }
        $a = implode(', ', $names);

        // Normalize values
        $a = strtolower(strval($a));
        $b = strtolower(strval($b));

        return match ($operator) {
            ValueOperator::CONTAINS     => str_contains($a, $b),
            ValueOperator::NOT_CONTAINS => !str_contains($a, $b),

            // Unsupported operators
            default => false,
        };
    }
}

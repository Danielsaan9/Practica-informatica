<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ValueOperator;
use Glpi\Form\Migration\ConditionHandlerDataConverterInterface;
use Glpi\Form\Migration\UnresolvedConditionValueException;
use Override;

final class MultipleChoiceFromValuesConditionHandler implements
    ConditionHandlerInterface,
    ConditionHandlerDataConverterInterface
{
    use ArrayConditionHandlerTrait;

    public function __construct(
        private array $values,
    ) {}

    #[Override]
    public function getSupportedValueOperators(): array
    {
        return $this->getSupportedArrayValueOperators();
    }

    #[Override]
    public function getTemplate(): string
    {
        return '/pages/admin/form/condition_handler_templates/dropdown_multiple.html.twig';
    }

    #[Override]
    public function getTemplateParameters(ConditionData $condition): array
    {
        return [
            'values' => $this->values,
        ];
    }

    #[Override]
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool {
        // During form rendering, applyValueOperator is called to compute items
        // visibility using the question default value, which is stored as a
        // comma separated list of options.
        if (is_string($a)) {
            $a = explode(',', $a);
        }

        return $this->applyArrayValueOperator($a, $operator, $b);
    }

    #[Override]
    public function convertConditionValue(string $value): array
    {
        $index = array_search($value, $this->values, true);
        if ($index === false) {
            throw new UnresolvedConditionValueException();
        }

        return [$index];
    }
}

<?php

namespace Glpi\Form\Condition\ConditionHandler;

use CommonDBTM;
use CommonTreeDropdown;
use Glpi\DBAL\JsonFieldInterface;
use Glpi\Exception\TooManyResultsException;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ValueOperator;
use Glpi\Form\Migration\ConditionHandlerDataConverterInterface;
use Glpi\Form\Migration\FallbackToAnotherOperatorException;
use Glpi\Form\Migration\UnresolvedConditionValueException;
use Override;

use function Safe\json_decode;

final class ItemConditionHandler implements ConditionHandlerInterface, ConditionHandlerDataConverterInterface
{
    use ArrayConditionHandlerTrait;

    /**
     * @param class-string<CommonDBTM> $itemtype
     * @param bool $is_multiple_items Whether the condition handler should handle multiple items
     */
    public function __construct(
        private string $itemtype,
        private bool $is_multiple_items = false,
    ) {}

    #[Override]
    public function getSupportedValueOperators(): array
    {
        $operators = [
            ValueOperator::EQUALS,
            ValueOperator::NOT_EQUALS,
        ];

        if ($this->is_multiple_items) {
            $operators[] = ValueOperator::CONTAINS;
            $operators[] = ValueOperator::NOT_CONTAINS;
        }

        return $operators;
    }

    #[Override]
    public function getTemplate(): string
    {
        return '/pages/admin/form/condition_handler_templates/item_dropdown.html.twig';
    }

    #[Override]
    public function getTemplateParameters(ConditionData $condition): array
    {
        return ['itemtype' => $this->itemtype, 'is_multiple_items' => $this->is_multiple_items];
    }

    #[Override]
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool {
        // During form rendering, applyValueOperator is called to compute questions visibility
        // Default value is used as value
        if (!is_array($a) && json_validate($a)) {
            $a = json_decode($a, true);

            // itemtype key is not provided in the default value, use the one from the question
            $a['itemtype'] = $this->itemtype;
        }

        // Itemtype must match before comparing ids
        $a_itemtype = is_array($a) ? ($a['itemtype'] ?? null) : null;
        $b_itemtype = is_array($b) ? ($b['itemtype'] ?? null) : null;
        if ($a_itemtype !== $b_itemtype) {
            return match ($operator) {
                ValueOperator::NOT_EQUALS, ValueOperator::NOT_CONTAINS => true,
                default => false,
            };
        }

        // Extract and normalize items_ids from both sides for comparison
        $a_ids = is_array($a) ? array_values((array) ($a['items_ids'] ?? [])) : [];
        $b_ids = is_array($b) ? array_values((array) ($b['items_ids'] ?? [])) : [];

        return $this->applyArrayValueOperator($a_ids, $operator, $b_ids);
    }

    #[Override]
    public function convertConditionValue(string $value): array|int
    {
        $nameFields = [];
        $item = getItemForItemtype($this->itemtype);
        if ($item instanceof CommonTreeDropdown) {
            $nameFields[] = $item::getCompleteNameField();
        }
        $nameFields[] = $item::getNameField();

        try {
            foreach ($nameFields as $nameField) {
                // Retrieve item by name
                if ($item->getFromDBByCrit([$nameField => $value])) {
                    return [
                        'itemtype' => $this->itemtype,
                        'items_ids' => [$item->getID()],
                    ];
                }
            }
        } catch (TooManyResultsException $e) {
            // We failed to find a single item for the given name.
            // This can happen because formcreator use raw strings, which do
            // not garantee that we will get a single result while checking by
            // name.
            // We can bypass this by falling back to a the contains operator,
            // which use raw text.
            $fallback = new FallbackToAnotherOperatorException(
                $e->getMessage(),
                $e->getCode(),
                $e,
            );
            $fallback->setOperator(ValueOperator::CONTAINS);
            $fallback->setValue($value);
            throw $fallback;
        }

        throw new UnresolvedConditionValueException();
    }
}

<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionData;
use Glpi\Form\Condition\ValueOperator;

interface ConditionHandlerInterface
{
    /** @return ValueOperator[] */
    public function getSupportedValueOperators(): array;

    /**
     * Path to a valid twig template.
     *
     * The template must be able to display the needed input to represent this
     * condition value, using the following parameters:
     * - input_value: the value of the input that will be displayed
     * - input_name: the name that must be applied to the input
     * - input_label: the label of the input
     *
     * It will also receive any parameters returned by getTemplateParameters().
     *
     * A specific `data-glpi-conditions-editor-value` attribute must be added to
     * the input to allow the editor to target this input when needed.
     */
    public function getTemplate(): ?string;

    /**
     * Returns an array of parameters that will be passed to the template
     * defined in getTemplate().
     *
     * @param ConditionData $condition
     */
    public function getTemplateParameters(ConditionData $condition): array;

    /**
     * Applies the given value operator to the two given values.
     * @param mixed         $a        Input value
     * @param ValueOperator $operator The operator to apply
     * @param mixed         $b        Condition value
     * @return bool Result of the operation
     */
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool;
}

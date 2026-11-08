<?php

namespace Glpi\Form\Condition;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ConditionHandler\ConditionHandlerInterface;

/**
 * Items that implements this interface can be used as a criteria in a condition.
 */
interface UsedAsCriteriaInterface
{
    /**
     * Get the condition handlers that can be used with this item.
     *
     * @param JsonFieldInterface|null $question_config The question config
     * @return array<ConditionHandlerInterface> The condition handlers
     */
    public function getConditionHandlers(
        ?JsonFieldInterface $question_config
    ): array;

    /**
     * Get the supported value operators for this item.
     *
     * @param JsonFieldInterface|null $question_config The question config
     * @return array<ValueOperator> The supported value operators
     */
    public function getSupportedValueOperators(
        ?JsonFieldInterface $question_config
    ): array;
}

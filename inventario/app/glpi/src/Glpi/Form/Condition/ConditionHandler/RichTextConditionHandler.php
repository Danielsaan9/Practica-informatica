<?php

namespace Glpi\Form\Condition\ConditionHandler;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Condition\ValueOperator;
use Override;

final class RichTextConditionHandler extends StringConditionHandler implements ConditionHandlerInterface
{
    #[Override]
    public function applyValueOperator(
        mixed $a,
        ValueOperator $operator,
        mixed $b,
        ?JsonFieldInterface $config,
    ): bool {
        // Remove HTML tags.
        $a = strip_tags(strval($a));
        $b = strip_tags(strval($b));

        return parent::applyValueOperator($a, $operator, $b, $config);
    }
}

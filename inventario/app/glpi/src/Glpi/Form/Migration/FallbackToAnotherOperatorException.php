<?php

namespace Glpi\Form\Migration;

use Exception;
use Glpi\Form\Condition\ValueOperator;

final class FallbackToAnotherOperatorException extends Exception
{
    private ValueOperator $operator;
    private mixed $value;

    public function setOperator(ValueOperator $operator): void
    {
        $this->operator = $operator;
    }

    public function getOperator(): ValueOperator
    {
        return $this->operator;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}

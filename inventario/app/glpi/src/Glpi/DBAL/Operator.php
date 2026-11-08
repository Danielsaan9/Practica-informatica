<?php

namespace Glpi\DBAL;

use InvalidArgumentException;

enum Operator: string
{
    case AND = 'AND';
    case OR = 'OR';
    case NONE = '';

    public static function fromString(?string $operator): Operator
    {
        if ($operator !== null) {
            $operator = strtoupper($operator);
        }
        return match ($operator) {
            'AND' => self::AND,
            'OR' => self::OR,
            null, '' => self::NONE,
            default => throw new InvalidArgumentException("Invalid operator: $operator"),
        };
    }
}

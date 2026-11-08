<?php

namespace Glpi\Api\HL\RSQL;

enum Error: int
{
    case UNKNOWN_PROPERTY = 1;
    case UNKNOWN_OPERATOR = 2;
    case MAPPED_PROPERTY = 3;

    public function getMessage(): string
    {
        return match ($this) {
            self::UNKNOWN_PROPERTY => 'Unknown property',
            self::UNKNOWN_OPERATOR => 'Unknown operator',
            self::MAPPED_PROPERTY => 'Mapped properties cannot be used in RSQL',
        };
    }
}

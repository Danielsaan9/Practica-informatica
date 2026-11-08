<?php

namespace Glpi\Api\HL\GraphQL\Type;

use GraphQL\Type\Definition\StringType;

use function Safe\strtotime;

/**
 * GraphQL type for RFC 3339 date-time strings.
 */
class DateTimeType extends StringType
{
    public string $name = 'RFC3339DateTime';

    private static ?DateTimeType $instance = null;

    public function serialize($value): string
    {
        $value = parent::serialize($value);
        return date(DATE_RFC3339, strtotime($value));
    }

    public static function dateTime(): self
    {
        if (self::$instance === null) {
            self::$instance = new DateTimeType();
        }
        return self::$instance;
    }
}

<?php

namespace Glpi\Toolbox;

class ArrayNormalizer
{
    public static function normalizeValues(array $array, callable $values_normalizer, bool $preserve_keys = false): array
    {
        $cleaned_array = [];

        foreach ($array as $key => $value) {
            $value = call_user_func($values_normalizer, $value);

            if ($preserve_keys) {
                $cleaned_array[$key] = $value;
            } else {
                $cleaned_array[] = $value;
            }
        }

        return $cleaned_array;
    }
}

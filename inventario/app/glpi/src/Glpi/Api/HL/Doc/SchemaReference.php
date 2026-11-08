<?php

namespace Glpi\Api\HL\Doc;

use ArrayAccess;
use Glpi\Api\HL\Schemas;

/**
 * @implements ArrayAccess<'ref', string>
 */
final class SchemaReference implements ArrayAccess
{
    public function __construct(
        private string $ref
    ) {}

    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @param array|string $ref The schema reference
     * @param string $controller The controller that is calling this method
     * @param array $attributes Attributes from the request, if we know them to help resolve placeholders in the ref.
     *                          For example: /Assets/{itemtype} when the ref is "{itemtype}". The attributes array should contain an "itemtype" key.
     * @param string $api_version The API version
     * @return array|null
     */
    public static function resolveRef($ref, string $controller, array $attributes, string $api_version): ?array
    {
        $known_schemas = Schemas::getInstance($api_version)->getAllSchemas();
        if (!is_string($ref) && $ref !== null) {
            $ref = $ref['ref'];
        }

        $is_ref_array = str_ends_with($ref, '[]');
        $ref_name = $is_ref_array ? substr($ref, 0, -2) : $ref;

        foreach ($attributes as $key => $value) {
            $ref_name = str_replace('{' . $key . '}', $value, $ref_name);
        }

        $match = null;
        if (isset($known_schemas[$ref_name])) {
            $match = $known_schemas[$ref_name];
        } else {
            // no exact match, find all schemas whose key, after the first -, matches the ref
            $matches = [];
            foreach ($known_schemas as $key => $schema) {
                if (str_contains($key, '-')) {
                    $key = substr($key, strpos($key, '-') + 1);
                }
                if (strcasecmp(trim($key), $ref_name) === 0) {
                    $matches[] = $schema;
                }
            }

            $controller_matches = array_filter($matches, static fn($schema) => $schema['x-controller'] === $controller);
            if (count($controller_matches) > 0) {
                $match = reset($controller_matches);
            }

            if (count($matches) > 0) {
                $match = reset($matches);
            }
        }

        if ($match === null) {
            return null;
        }
        return $is_ref_array ? ['type' => 'array', 'items' => $match] : $match;
    }

    public function offsetExists(mixed $offset): bool
    {
        return $offset === 'ref';
    }

    public function offsetGet(mixed $offset): mixed
    {
        if ($offset === 'ref') {
            return $this->ref;
        }
        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === 'ref') {
            $this->ref = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        if ($offset === 'ref') {
            $this->ref = '';
        }
    }
}

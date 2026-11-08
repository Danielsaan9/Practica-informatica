<?php

namespace Glpi\Api\HL\GraphQL;

/**
 * An object fetched and cached during a GraphQL request.
 * May be an incomplete representation of the object, depending on the requested fields.
 */
class CachedObject
{
    /** @param array<string, mixed> $data */
    public function __construct(
        public array $data = []
    ) {}

    /**
     * @return string[]
     */
    public function getFields(): array
    {
        return array_keys($this->data);
    }
}

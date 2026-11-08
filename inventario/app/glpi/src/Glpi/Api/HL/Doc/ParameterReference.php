<?php

namespace Glpi\Api\HL\Doc;

use Glpi\Api\HL\OpenAPIGenerator;

final readonly class ParameterReference extends Parameter
{
    public function __construct(string $name)
    {
        $resolved = OpenAPIGenerator::getParameterComponents()[$name] ?? null;
        if (!$resolved) {
            throw new \InvalidArgumentException("Parameter reference '$name' not found in components.");
        }
        parent::__construct(
            name: $name,
            schema: Schema::fromArray($resolved['schema']),
            description: $resolved['description'] ?? '',
            location: $resolved['in'] ?? Parameter::LOCATION_QUERY,
            example: $resolved['example'] ?? null,
            required: $resolved['required'] ?? false
        );
    }

    public function getComponentPath(): string
    {
        return '#/components/parameters/' . $this->getName();
    }
}

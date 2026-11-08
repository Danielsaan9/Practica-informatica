<?php

namespace Glpi\Api\HL\Doc;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class CreateRoute extends Route
{
    public function __construct(string $schema_name, ?string $description = null)
    {
        parent::__construct(
            description: $description ?? 'Create a new ' . $schema_name,
            parameters: [
                new Parameter(
                    name: '_',
                    schema: new SchemaReference($schema_name),
                    location: Parameter::LOCATION_BODY,
                ),
            ]
        );
    }
}

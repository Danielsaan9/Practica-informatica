<?php

namespace Glpi\Api\HL\Doc;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class UpdateRoute extends Route
{
    public function __construct(string $schema_name, ?string $description = null)
    {
        parent::__construct(
            description: $description ?? 'Update an existing ' . $schema_name,
            parameters: [
                new Parameter(
                    name: '_',
                    schema: new SchemaReference($schema_name),
                    location: Parameter::LOCATION_BODY,
                ),
            ],
            responses: [
                new Response(
                    schema: new SchemaReference($schema_name),
                    description: 'The updated ' . $schema_name
                ),
            ]
        );
    }
}

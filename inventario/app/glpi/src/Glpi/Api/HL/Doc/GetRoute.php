<?php

namespace Glpi\Api\HL\Doc;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class GetRoute extends Route
{
    public function __construct(string $schema_name, ?string $description = null)
    {
        parent::__construct(
            description: $description ?? 'Get an existing ' . $schema_name,
            responses: [
                new Response(schema: new SchemaReference($schema_name)),
            ]
        );
    }
}

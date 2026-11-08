<?php

namespace Glpi\Api\HL\Doc;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class SearchRoute extends Route
{
    public function __construct(?string $schema_name = null, ?string $description = null)
    {
        $responses = $schema_name ? [new Response(new SchemaReference($schema_name . '[]'))] : [];
        $description ??= $schema_name ? 'List or search for ' . ucfirst(getPlural($schema_name)) : 'List or search for items';
        parent::__construct(
            description: $description,
            parameters: [
                new ParameterReference('filter'),
                new ParameterReference('start'),
                new ParameterReference('limit'),
                new ParameterReference('sort'),
            ],
            responses: $responses
        );
    }
}

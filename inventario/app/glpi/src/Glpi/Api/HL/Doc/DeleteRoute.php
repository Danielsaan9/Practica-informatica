<?php

namespace Glpi\Api\HL\Doc;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class DeleteRoute extends Route
{
    public function __construct(string $schema_name, ?string $description = null)
    {
        parent::__construct(
            description: $description ?? 'Delete a ' . $schema_name,
        );
    }
}

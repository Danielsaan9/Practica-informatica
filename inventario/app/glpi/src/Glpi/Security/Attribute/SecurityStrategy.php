<?php

namespace Glpi\Security\Attribute;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final readonly class SecurityStrategy
{
    public function __construct(
        public string $strategy,
    ) {}
}

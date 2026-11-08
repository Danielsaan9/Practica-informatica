<?php

namespace Glpi\Form\Export\Specification;

final class CustomTypeRequirementSpecification
{
    public function __construct(
        public string $itemtype = "",
    ) {}
}

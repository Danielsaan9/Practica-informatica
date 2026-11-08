<?php

namespace Glpi\Form\Export\Specification;

use CommonDBTM;
use CommonTreeDropdown;

final class DataRequirementSpecification
{
    public function __construct(
        public string $itemtype = "",
        public string $name = "",
    ) {}

    public static function fromItem(CommonDBTM $item): self
    {
        if ($item instanceof CommonTreeDropdown) {
            $name = $item->getName(['complete' => true]);
        } else {
            $name = $item->getName();
        }
        return new self($item::class, $name);
    }
}

<?php

namespace Glpi\Form\Destination;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class HasFormTags
{
    public function __construct() {}
}

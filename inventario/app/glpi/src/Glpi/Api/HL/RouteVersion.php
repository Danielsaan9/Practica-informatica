<?php

namespace Glpi\Api\HL;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class RouteVersion
{
    public function __construct(
        /** @var string $introduced The first API version this route is available in */
        public string $introduced,
        /** @var string $deprecated The API version this route is deprecated in */
        public string $deprecated = '',
        /** @var string $removed The API version this route is removed in */
        public string $removed = ''
    ) {}
}

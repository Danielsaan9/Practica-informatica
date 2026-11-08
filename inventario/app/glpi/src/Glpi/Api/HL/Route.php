<?php

namespace Glpi\Api\HL;

use Attribute;
use Glpi\Api\HL\Middleware\AbstractMiddleware;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class Route
{
    /**
     * Access to the route is allowed without any authentication.
     */
    public const SECURITY_NONE = 0;

    /**
     * Access to the route is allowed only if the user is logged in (valid Glpi-Session-Token header).
     */
    public const SECURITY_AUTHENTICATED = 1;

    public const DEFAULT_PRIORITY = 10;

    public function __construct(
        public string $path,
        /** @var string[] $methods */
        public array $methods = [],
        /** @var array<string, string|array> $requirements */
        public array $requirements = [],
        public int $priority = self::DEFAULT_PRIORITY,
        public int $security_level = self::SECURITY_AUTHENTICATED,
        /** @var string[] */
        public array $tags = [],
        /** @var class-string<AbstractMiddleware>[] */
        public array $middlewares = [],
        /** @var string[] $scopes */
        public array $scopes = ['api'],
    ) {}
}

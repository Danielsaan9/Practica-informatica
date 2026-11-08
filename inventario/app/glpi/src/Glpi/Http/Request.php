<?php

namespace Glpi\Http;

use GuzzleHttp\Psr7\ServerRequest;

class Request extends ServerRequest
{
    /** @var array<string, mixed> */
    private array $custom_attributes = [];

    /** @var array<string, mixed> */
    private array $parameters = [];

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->getAttributes());
    }

    public function getAttribute($attribute, $default = null): mixed
    {
        return $this->getAttributes()[$attribute] ?? $default;
    }

    public function getAttributes(): array
    {
        return array_merge(parent::getAttributes(), $this->custom_attributes);
    }

    public function setAttribute(string $name, mixed $value): void
    {
        $this->custom_attributes[$name] = $value;
    }

    public function hasParameter(string $name): bool
    {
        return array_key_exists($name, $this->parameters);
    }

    public function getParameter(string $name): mixed
    {
        return $this->parameters[$name];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParsedBody()
    {
        return $this->getParameters();
    }

    public function setParameter(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }
}

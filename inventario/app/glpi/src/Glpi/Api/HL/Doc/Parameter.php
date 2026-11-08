<?php

namespace Glpi\Api\HL\Doc;

use ArrayAccess;

/**
 * @implements ArrayAccess<string, null|string|bool|Schema>
 */
readonly class Parameter implements ArrayAccess
{
    public const LOCATION_QUERY = 'query';
    public const LOCATION_PATH = 'path';
    public const LOCATION_HEADER = 'header';
    public const LOCATION_COOKIE = 'cookie';
    public const LOCATION_BODY = 'body';

    public function __construct(
        protected string $name,
        protected Schema|SchemaReference $schema,
        protected string $description = '',
        protected string $location = self::LOCATION_QUERY,
        protected ?string $example = null,
        protected bool $required = false
    ) {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return Schema|SchemaReference
     */
    public function getSchema(): Schema|SchemaReference
    {
        return $this->schema;
    }

    /**
     * @return string|null
     */
    public function getExample(): ?string
    {
        return $this->example;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        if ($this->schema instanceof Schema) {
            return $this->schema->getDefault();
        }
        return null;
    }

    /**
     * @return bool
     */
    public function getRequired(): bool
    {
        return $this->required;
    }

    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (property_exists($this, $offset)) {
            return $this->$offset;
        }
        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        //Not supported
    }

    public function offsetUnset(mixed $offset): void
    {
        //Not supported
    }
}

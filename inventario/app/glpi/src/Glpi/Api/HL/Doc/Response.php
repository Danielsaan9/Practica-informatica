<?php

namespace Glpi\Api\HL\Doc;

use ArrayAccess;

/**
 * @implements ArrayAccess<string, null|string|bool|Schema|SchemaReference>
 */
class Response implements ArrayAccess
{
    public function __construct(
        private Schema|SchemaReference|null $schema,
        private string $description = '',
        private array $headers = [],
        private array $examples = [],
        private string $media_type = 'application/json',
        private int $status_code = 200,
    ) {}

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return Schema|SchemaReference|null
     */
    public function getSchema(): Schema|SchemaReference|null
    {
        return $this->schema;
    }

    public function isReference(): bool
    {
        return $this->schema instanceof SchemaReference;
    }

    /**
     * @return array
     */
    public function getExamples(): array
    {
        return $this->examples;
    }

    /**
     * @return string
     */
    public function getMediaType(): string
    {
        return $this->media_type;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status_code;
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

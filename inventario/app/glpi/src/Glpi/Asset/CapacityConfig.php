<?php

namespace Glpi\Asset;

use JsonSerializable;

class CapacityConfig implements JsonSerializable
{
    public function __construct(
        private array $values = []
    ) {}

    public function setValue(string $key, mixed $value): self
    {
        $this->values[$key] = $value;
        return $this;
    }

    public function getValue(string $key, mixed $default = null): mixed
    {
        return \array_key_exists($key, $this->values) ? $this->values[$key] : $default;
    }

    public function jsonSerialize(): array
    {
        return $this->values;
    }
}

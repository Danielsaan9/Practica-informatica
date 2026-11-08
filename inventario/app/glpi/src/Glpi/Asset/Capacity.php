<?php

namespace Glpi\Asset;

use JsonSerializable;

class Capacity implements JsonSerializable
{
    public function __construct(
        private string $name,
        private CapacityConfig $config = new CapacityConfig(),
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getConfig(): CapacityConfig
    {
        return $this->config;
    }

    public function setConfig(CapacityConfig $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'config' => $this->config->jsonSerialize(),
        ];
    }
}

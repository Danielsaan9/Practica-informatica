<?php

namespace Glpi\Knowbase;

final readonly class EditorAction
{
    /** @param array<string, string|int> $params */
    public function __construct(
        public string $label,
        public string $icon,
        public EditorActionType $type,
        public array $params = [],
        public ?int $counter = null,
        public bool $is_danger = false,
    ) {}
}

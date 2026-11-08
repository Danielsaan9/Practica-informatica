<?php

namespace Glpi\Knowbase\History;

use Override;

final class CreationEvent implements HistoryEventInterface
{
    public function __construct(
        private ?string $date,
        private int $author,
    ) {}

    #[Override]
    public function getLabel(): string
    {
        return __("Current version");
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Created by");
    }

    #[Override]
    public function getDate(): ?string
    {
        return $this->date;
    }

    #[Override]
    public function getAuthor(): int
    {
        return $this->author;
    }
}

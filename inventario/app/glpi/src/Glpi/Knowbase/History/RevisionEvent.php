<?php

namespace Glpi\Knowbase\History;

use Override;

class RevisionEvent implements HistoryEventInterface
{
    public function __construct(
        private int $id,
        protected int $index,
        private ?string $date,
        private int $author_id,
    ) {}

    #[Override]
    public function getLabel(): string
    {
        return \sprintf(__('Version %s'), $this->index);
    }

    #[Override]
    public function getDescription(): string
    {
        return $this->index === 1 ? __("Created by") : __("Updated by");
    }

    #[Override]
    public function getDate(): ?string
    {
        return $this->date;
    }

    #[Override]
    public function getAuthor(): int
    {
        return $this->author_id;
    }

    public function getRevisionId(): int
    {
        return $this->id;
    }
}

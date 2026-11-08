<?php

namespace Glpi\Knowbase;

use Html;

final readonly class LastUpdateInfo
{
    public function __construct(
        private ?string $author_link,
        private ?string $author_name,
        private ?string $date,
        private bool $can_view_author,
    ) {}

    public function getAuthorLink(): ?string
    {
        return $this->author_link;
    }

    public function getAuthorName(): string
    {
        return $this->author_name ?: __("Deleted user");
    }

    public function getRawDate(): ?string
    {
        return $this->date;
    }

    public function getRelativeDate(): ?string
    {
        if ($this->date === null) {
            return null;
        }

        return Html::timestampToRelativeStr($this->date);
    }

    public function canViewAuthor(): bool
    {
        return $this->can_view_author;
    }
}

<?php

namespace Glpi\Knowbase\History;

use Dropdown;
use Override;

final class CurrentTranslationEvent implements HistoryEventInterface
{
    public function __construct(
        private string $language,
        private ?string $date,
        private int $author,
    ) {}

    #[Override]
    public function getLabel(): string
    {
        $language = Dropdown::getLanguageName($this->language);
        return \sprintf(__('%s — Current version'), $language);
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Updated by");
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

    public function getLanguage(): string
    {
        return $this->language;
    }
}

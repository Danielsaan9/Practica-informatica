<?php

namespace Glpi\Knowbase\History;

use Dropdown;
use Override;

final class TranslationRevisionEvent extends RevisionEvent
{
    public function __construct(
        int $id,
        int $index,
        ?string $date,
        int $author_id,
        private string $language,
    ) {
        parent::__construct($id, $index, $date, $author_id);
    }

    #[Override]
    public function getLabel(): string
    {
        $language_name = Dropdown::getLanguageName($this->language);
        return \sprintf(__('%s — Version %s'), $language_name, $this->index);
    }

    public function getLanguage(): string
    {
        return $this->language;
    }
}

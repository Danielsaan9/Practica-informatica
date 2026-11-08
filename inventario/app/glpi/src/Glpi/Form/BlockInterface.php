<?php

namespace Glpi\Form;

use Glpi\ItemTranslation\Context\ProvideTranslationsInterface;

interface BlockInterface extends ProvideTranslationsInterface
{
    public function displayBlockForEditor(bool $can_update, bool $allow_unauthenticated): void;

    public function getUntitledLabel(): string;

    public function getSection(): Section;
}

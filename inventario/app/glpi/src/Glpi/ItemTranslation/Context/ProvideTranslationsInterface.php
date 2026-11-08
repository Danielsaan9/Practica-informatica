<?php

namespace Glpi\ItemTranslation\Context;

/**
 * Must be implemented by classes that provide translations.
 */
interface ProvideTranslationsInterface
{
    /**
     * Returns the list of form translations handlers.
     *
     * @return array<string, array<int, TranslationHandler>>
     */
    public function listTranslationsHandlers(): array;
}

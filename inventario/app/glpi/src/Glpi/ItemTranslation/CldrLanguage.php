<?php

namespace Glpi\ItemTranslation;

use Gettext\Languages\Language;
use LogicException;

final class CldrLanguage
{
    private Language $language;

    public function __construct(string $lang_identifier)
    {
        $language = Language::getById($lang_identifier);
        if ($language === null) {
            throw new LogicException(sprintf('Invalid lang `%s`.', $lang_identifier));
        }

        $this->language = $language;
    }

    /**
     * Get the plural key corresponding to the given number.
     *
     * @param int $number
     * @return string
     */
    final public function getPluralKey(int $number): string
    {
        $formula_to_compute = str_replace('n', (string) $number, $this->language->formula);
        $category_index_number = eval("return $formula_to_compute;");

        if (!\array_key_exists($category_index_number, $this->language->categories)) {
            throw new LogicException();
        }

        return $this->language->categories[$category_index_number]->id;
    }
}

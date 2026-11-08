<?php

namespace Glpi\Form;

use CommonGLPI;
use Dropdown;
use Glpi\Application\View\TemplateRenderer;
use Glpi\ItemTranslation\ItemTranslation;
use Override;
use Session;

final class FormTranslation extends ItemTranslation
{
    public static string $rightname = 'form';

    #[Override]
    public static function getTypeName($nb = 0)
    {
        return _n('Form translation', 'Form translations', $nb);
    }

    #[Override]
    public static function getIcon(): string
    {
        return "ti ti-language";
    }

    #[Override]
    public static function getTable($classname = null)
    {
        if (is_a($classname ?? self::class, ItemTranslation::class, true)) {
            return parent::getTable(ItemTranslation::class);
        }
        return parent::getTable($classname);
    }

    #[Override]
    public function getName($options = []): string
    {
        return Dropdown::getLanguageName($this->fields['language']);
    }

    #[Override]
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): string
    {
        if ($item instanceof Form) {
            $count = 0;
            if ($_SESSION['glpishow_count_on_tabs']) {
                $count = countDistinctElementsInTable(
                    static::getTable(),
                    'language',
                    ['items_id' => $item->getID(), 'itemtype' => $item::class]
                );
            }

            return self::createTabEntry(
                self::getTypeName(Session::getPluralNumber()),
                $count,
            );
        } elseif ($item instanceof FormTranslation) {
            return self::createTabEntry($item->getName());
        }

        return '';
    }

    #[Override]
    public static function displayTabContentForItem(
        CommonGLPI $item,
        $tabnum = 1,
        $withtemplate = 0
    ) {
        if ($item instanceof Form) {
            $translations = array_reduce(
                self::getTranslationsForItem($item),
                fn($carry, $translation) => $carry + [$translation->fields['language'] => $translation],
                []
            );
            $available_languages = self::getLanguagesCanBeAddedToTranslation($item->getID());
            TemplateRenderer::getInstance()->display('pages/admin/form/form_translations.html.twig', [
                'item'                => $item,
                'translations'        => $translations,
                'available_languages' => $available_languages,
            ]);

            return true;
        }

        return false;
    }

    public static function getTranslationsForForm(Form $form): array
    {
        return array_merge(
            self::getTranslationsForItem($form),
            ...array_map(
                fn($section) => self::getTranslationsForItem($section),
                $form->getSections()
            ),
            ...array_map(
                fn($question) => self::getTranslationsForItem($question),
                $form->getQuestions()
            ),
            ...array_map(
                fn($comment) => self::getTranslationsForItem($comment),
                $form->getFormComments()
            ),
        );
    }

    /**
     * Get remaining languages that can be added to a form translation
     *
     * @param int $form_id
     * @return array<string, string> List of languages (code => name)
     */
    public static function getLanguagesCanBeAddedToTranslation(int $form_id): array
    {
        $form_translations = array_map(
            fn(ItemTranslation $translation) => $translation->fields['language'],
            self::getTranslationsForItem(Form::getById($form_id))
        );

        return array_combine(
            array_diff(array_keys(Dropdown::getLanguages()), $form_translations),
            array_map(
                fn($language) => Dropdown::getLanguageName($language),
                array_diff(array_keys(Dropdown::getLanguages()), $form_translations)
            )
        );
    }

    public static function getSystemSQLCriteria(?string $tablename = null): array
    {
        $criteria = [
            'itemtype' => [Form::class, Section::class, Question::class, Comment::class],
        ];
        return [crc32(serialize($criteria)) => $criteria];
    }
}

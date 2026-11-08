<?php

namespace Glpi\Application\View\Extension;

use CommonDBTM;
use Dropdown;
use Glpi\Form\FormTranslation;
use Glpi\Helpdesk\HelpdeskTranslation;
use Glpi\ItemTranslation\Context\ProvideTranslationsInterface;
use Locale;
use Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @since 10.0.0
 */
class I18nExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('__', '__'),
            new TwigFunction('_n', '_n'),
            new TwigFunction('_x', '_x'),
            new TwigFunction('_nx', '_nx'),
            new TwigFunction('get_current_locale', [$this, 'getCurrentLocale']),
            new TwigFunction('get_language_name', [Dropdown::class, 'getLanguageName']),
            new TwigFunction('get_plural_number', [Session::class, 'getPluralNumber']),
            new TwigFunction('translate_form_item_key', $this->translateFormItemKey(...)),
            new TwigFunction('translate_helpdesk_item_key', $this->translateHelpdeskItemKey(...)),
        ];
    }

    public function getCurrentLocale(): array
    {
        return Locale::parseLocale($_SESSION['glpilanguage'] ?? 'en_GB');
    }

    public function translateFormItemKey(
        CommonDBTM&ProvideTranslationsInterface $item,
        string $key,
        int $count = 1
    ): ?string {
        return FormTranslation::translate($item, $key, $count);
    }

    public function translateHelpdeskItemKey(
        CommonDBTM&ProvideTranslationsInterface $item,
        string $key,
        int $count = 1
    ): ?string {
        return HelpdeskTranslation::translate($item, $key, $count);
    }
}

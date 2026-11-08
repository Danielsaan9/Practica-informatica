<?php

namespace Glpi\Helpdesk\Tile;

use CommonDBTM;
use Glpi\Helpdesk\HelpdeskTranslation;
use Glpi\ItemTranslation\Context\ProvideTranslationsInterface;
use Glpi\ItemTranslation\Context\TranslationHandler;
use Glpi\Session\SessionInfo;
use Glpi\Toolbox\URL;
use Glpi\UI\IllustrationManager;
use Override;

final class ExternalPageTile extends CommonDBTM implements TileInterface, ProvideTranslationsInterface
{
    use TileRightTrait;

    public static string $rightname = 'config';

    public const TRANSLATION_KEY_TITLE = 'title';
    public const TRANSLATION_KEY_DESCRIPTION = 'description';

    #[Override]
    public function getWeight(): int
    {
        return 30;
    }

    #[Override]
    public function getLabel(): string
    {
        return __("External page");
    }

    #[Override]
    public function getTitle(): string
    {
        return HelpdeskTranslation::translate(
            $this,
            self::TRANSLATION_KEY_TITLE
        ) ?? '';
    }

    #[Override]
    public function getDescription(): string
    {
        return HelpdeskTranslation::translate(
            $this,
            self::TRANSLATION_KEY_DESCRIPTION
        ) ?? '';
    }

    #[Override]
    public function getIllustration(): string
    {
        return ($this->fields['illustration'] ?? '') ?: IllustrationManager::DEFAULT_ILLUSTRATION;
    }

    #[Override]
    public function getTileUrl(): string
    {
        return URL::sanitizeURL($this->fields['url'] ?? '');
    }

    #[Override]
    public function isAvailable(SessionInfo $session_info): bool
    {
        return true;
    }

    #[Override]
    public function getDatabaseId(): int
    {
        return $this->fields['id'];
    }

    #[Override]
    public function getConfigFieldsTemplate(): string
    {
        return "pages/admin/external_page_tile_config_fields.html.twig";
    }

    #[Override]
    public function cleanDBonPurge()
    {
        $this->deleteChildrenAndRelationsFromDb(
            [
                Item_Tile::class,
            ]
        );
    }

    #[Override]
    public function listTranslationsHandlers(): array
    {
        $handlers = [];
        $key = sprintf('%s_%d', self::class, $this->getID());
        $category_name = sprintf('%s: %s', $this->getLabel(), $this->fields['title'] ?? NOT_AVAILABLE);
        if (!empty($this->fields['title'])) {
            $handlers[$key][] = new TranslationHandler(
                item: $this,
                key: self::TRANSLATION_KEY_TITLE,
                name: __('Title'),
                value: $this->fields['title'],
                is_rich_text: false,
                category: $category_name
            );
        }
        if (!empty($this->fields['description'])) {
            $handlers[$key][] = new TranslationHandler(
                item: $this,
                key: self::TRANSLATION_KEY_DESCRIPTION,
                name: __('Description'),
                value: $this->fields['description'],
                is_rich_text: true,
                category: $category_name
            );
        }

        return $handlers;
    }
}

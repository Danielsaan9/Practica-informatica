<?php

namespace Glpi\Form\ServiceCatalog;

use CommonGLPI;
use Entity;
use Glpi\Application\View\TemplateRenderer;
use LogicException;
use Override;
use Session;

final class ServiceCatalog extends CommonGLPI
{
    #[Override]
    public static function getTypeName($nb = 0)
    {
        return __("Service catalog");
    }

    // TODO: Should be #[Override] but getIcon() is defined by CommonDBTM instead of CommonGLPI.
    public static function getIcon(): string
    {
        return "ti ti-library";
    }

    #[Override]
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0): string
    {
        // This tab is only available for service catalog leafs
        if (!($item instanceof ServiceCatalogLeafInterface)) {
            return "";
        }

        return self::createTabEntry(self::getTypeName());
    }

    #[Override]
    public static function displayTabContentForItem(
        CommonGLPI $item,
        $tabnum = 1,
        $withtemplate = 0
    ) {
        // This tab is only available for service catalog leafs
        if (!($item instanceof ServiceCatalogLeafInterface)) {
            return false;
        }

        $twig = TemplateRenderer::getInstance();
        echo $twig->render('pages/admin/form/service_catalog_tab.html.twig', [
            'item' => $item,
            'icon' => self::getIcon(),
        ]);

        return true;
    }

    #[Override]
    public static function getSearchURL($full = true): string
    {
        global $CFG_GLPI;

        return $full ? $CFG_GLPI['root_doc'] . '/ServiceCatalog' : '/ServiceCatalog';
    }

    #[Override]
    public static function canView(): bool
    {
        $session_info = Session::getCurrentSessionInfo();
        if ($session_info === null) {
            // Unlogged users can't render the service catalog
            return false;
        }

        $entity = Entity::getById($session_info->getCurrentEntityId());
        if (!$entity) {
            throw new LogicException(); // Can't happen
        }

        return $entity->isServiceCatalogEnabled();
    }
}

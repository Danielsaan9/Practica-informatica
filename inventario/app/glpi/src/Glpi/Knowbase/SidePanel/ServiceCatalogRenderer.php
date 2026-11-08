<?php

namespace Glpi\Knowbase\SidePanel;

use KnowbaseItem;
use Override;

final class ServiceCatalogRenderer implements RendererInterface
{
    #[Override]
    public function canView(KnowbaseItem $item): bool
    {
        return $item->can($item->getID(), UPDATE);
    }

    #[Override]
    public function getTemplate(): string
    {
        return "pages/tools/kb/modal/service_catalog.html.twig";
    }

    #[Override]
    public function getParams(KnowbaseItem $item): array
    {
        return [
            'id'                      => $item->getID(),
            'show_in_service_catalog' => (bool) ($item->fields['show_in_service_catalog'] ?? false),
            'description'             => $item->fields['description'] ?? '',
            'forms_categories_id'     => $item->fields['forms_categories_id'] ?? 0,
            'is_pinned'               => (bool) ($item->fields['is_pinned'] ?? false),
        ];
    }
}

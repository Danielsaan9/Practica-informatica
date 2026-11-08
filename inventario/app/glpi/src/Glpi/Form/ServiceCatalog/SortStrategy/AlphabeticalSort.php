<?php

namespace Glpi\Form\ServiceCatalog\SortStrategy;

use Glpi\Form\ServiceCatalog\ServiceCatalogItemInterface;

final class AlphabeticalSort extends AbstractSortStrategy
{
    protected function compareItems(
        ServiceCatalogItemInterface $a,
        ServiceCatalogItemInterface $b
    ): int {
        // Sort by title alphabetically
        return $a->getServiceCatalogItemTitle() <=> $b->getServiceCatalogItemTitle();
    }

    public function getLabel(): string
    {
        return __('Alphabetical');
    }

    public function getIcon(): string
    {
        return 'ti ti-sort-ascending-letters';
    }
}

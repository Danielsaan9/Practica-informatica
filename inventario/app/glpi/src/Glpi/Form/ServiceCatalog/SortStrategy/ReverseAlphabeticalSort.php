<?php

namespace Glpi\Form\ServiceCatalog\SortStrategy;

use Glpi\Form\ServiceCatalog\ServiceCatalogItemInterface;

final class ReverseAlphabeticalSort extends AbstractSortStrategy
{
    protected function compareItems(
        ServiceCatalogItemInterface $a,
        ServiceCatalogItemInterface $b
    ): int {
        // Sort by title in reverse alphabetical order
        return $b->getServiceCatalogItemTitle() <=> $a->getServiceCatalogItemTitle();
    }

    public function getLabel(): string
    {
        return __('Reverse alphabetical');
    }

    public function getIcon(): string
    {
        return 'ti ti-sort-descending-letters';
    }
}

<?php

namespace Glpi\Form\ServiceCatalog\SortStrategy;

use Glpi\Form\ServiceCatalog\ServiceCatalogItemInterface;

interface SortStrategyInterface
{
    /**
     * Sort an array of service catalog items
     *
     * @param ServiceCatalogItemInterface[] $items
     * @return ServiceCatalogItemInterface[]
     */
    public function sort(array $items): array;

    /**
     * Get the label of the sort strategy
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * Get the icon of the sort strategy
     *
     * @return string
     */
    public function getIcon(): string;
}

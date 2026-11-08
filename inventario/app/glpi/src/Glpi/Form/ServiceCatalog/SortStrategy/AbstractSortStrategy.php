<?php

namespace Glpi\Form\ServiceCatalog\SortStrategy;

use Glpi\Form\ServiceCatalog\ServiceCatalogCompositeInterface;
use Glpi\Form\ServiceCatalog\ServiceCatalogItemInterface;

abstract class AbstractSortStrategy implements SortStrategyInterface
{
    public function sort(array $items): array
    {
        usort($items, function (
            ServiceCatalogItemInterface $a,
            ServiceCatalogItemInterface $b,
        ) {
            // First compare pinned status
            if ($a->isServiceCatalogItemPinned() !== $b->isServiceCatalogItemPinned()) {
                return $b->isServiceCatalogItemPinned() <=> $a->isServiceCatalogItemPinned();
            }

            // Then handle composite vs non-composite (composite first)
            if (
                $a instanceof ServiceCatalogCompositeInterface
                && !($b instanceof ServiceCatalogCompositeInterface)
            ) {
                return -1;
            }

            if (
                !($a instanceof ServiceCatalogCompositeInterface)
                && $b instanceof ServiceCatalogCompositeInterface
            ) {
                return 1;
            }

            // Delegate the final comparison to the specific strategy
            return $this->compareItems($a, $b);
        });

        return $items;
    }

    /**
     * Compare two service catalog items according to the specific strategy
     *
     * @param ServiceCatalogItemInterface $a
     * @param ServiceCatalogItemInterface $b
     * @return int Negative if $a should come before $b, positive if $b should come before $a, 0 if equal
     */
    abstract protected function compareItems(
        ServiceCatalogItemInterface $a,
        ServiceCatalogItemInterface $b
    ): int;
}

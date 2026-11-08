<?php

namespace Glpi\Form\ServiceCatalog\SortStrategy;

use Glpi\Form\Form;
use Glpi\Form\ServiceCatalog\ServiceCatalogCompositeInterface;
use Glpi\Form\ServiceCatalog\ServiceCatalogItemInterface;
use KnowbaseItem;

final class PopularitySort extends AbstractSortStrategy
{
    protected function compareItems(
        ServiceCatalogItemInterface $a,
        ServiceCatalogItemInterface $b
    ): int {
        // Sort by popularity (view count or submission count)
        $a_popularity = $this->getPopularity($a);
        $b_popularity = $this->getPopularity($b);

        if ($a_popularity !== $b_popularity) {
            return $b_popularity <=> $a_popularity; // Higher popularity first
        }

        // If popularity is equal, fall back to alphabetical
        return $a->getServiceCatalogItemTitle() <=> $b->getServiceCatalogItemTitle();
    }

    private function getPopularity(ServiceCatalogItemInterface $item): int
    {
        // For Forms, we could use usage count
        if ($item instanceof Form) {
            return $item->getUsageCount();
        }

        // For KnowledgeBase items, we could use view count
        if ($item instanceof KnowbaseItem) {
            return $item->fields['view'];
        }

        // For categories, we could use the popularity sum of its children
        if ($item instanceof ServiceCatalogCompositeInterface) {
            $popularity = 0;
            foreach ($item->getChildren() as $child) {
                $popularity += $this->getPopularity($child);
            }
            return $popularity;
        }

        return 0;
    }

    public function getLabel(): string
    {
        return __('Most popular');
    }

    public function getIcon(): string
    {
        return 'ti ti-star';
    }
}

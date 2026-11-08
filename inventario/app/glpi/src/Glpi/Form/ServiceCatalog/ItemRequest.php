<?php

namespace Glpi\Form\ServiceCatalog;

use Glpi\Form\AccessControl\FormAccessParameters;
use Glpi\Form\ServiceCatalog\SortStrategy\SortStrategyEnum;

final class ItemRequest
{
    public function __construct(
        public FormAccessParameters $access_parameters,
        public string $filter = "",
        public ?int $category_id = null,
        public int $page = 1,
        public int $items_per_page = ServiceCatalogManager::ITEMS_PER_PAGE,
        public SortStrategyEnum $sort_strategy = SortStrategyEnum::POPULARITY,
        public ItemRequestContext $context = ItemRequestContext::SERVICE_CATALOG,
    ) {}

    public function getFormAccessParameters(): FormAccessParameters
    {
        return $this->access_parameters;
    }

    public function getFilter(): string
    {
        return $this->filter;
    }

    public function getCategoryID(): ?int
    {
        return $this->category_id;
    }

    public function getSortStrategy(): SortStrategyEnum
    {
        return $this->sort_strategy;
    }

    public function getContext(): ItemRequestContext
    {
        return $this->context;
    }
}

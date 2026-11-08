<?php

namespace Glpi\Form\ServiceCatalog;

/**
 * Represent a composite of a the service catalog tree.
 * When the user click on this item (e.g. a category), he goes down the tree
 * and will see new leaves and composites items.
 */
interface ServiceCatalogCompositeInterface extends ServiceCatalogItemInterface
{
    /**
     * Get the URL parameters needed to load this composite item's children using
     * the `/ServiceCatalog/Item` endpoint.
     */
    public function getChildrenUrlParameters(): string;

    /**
     * Get the service catalog's item request that will return the children
     * of the current composite item.
     *
     * The current request is available as the $item_request parameter as some
     * common parameters like the form access rights are likely to be reused.
     */
    public function getChildrenItemRequest(
        ItemRequest $item_request,
    ): ItemRequest;

    /** @param ServiceCatalogItemInterface[] $children */
    public function setChildren(array $children): void;

    /** @return ServiceCatalogItemInterface[] */
    public function getChildren(): array;
}

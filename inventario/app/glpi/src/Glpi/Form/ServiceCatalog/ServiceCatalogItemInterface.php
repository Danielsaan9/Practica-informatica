<?php

namespace Glpi\Form\ServiceCatalog;

interface ServiceCatalogItemInterface
{
    /**
     * Title that will be displayed in the service catalog
     * @return string
     */
    public function getServiceCatalogItemTitle(): string;

    /**
     * Description that will be displayed in the service catalog
     * @return string
     */
    public function getServiceCatalogItemDescription(): string;

    /**
     * Illustration that will be displayed in the service catalog
     * @return string
     */
    public function getServiceCatalogItemIllustration(): string;

    /**
     * Check if the item is pinned
     * @return bool
     */
    public function isServiceCatalogItemPinned(): bool;
}

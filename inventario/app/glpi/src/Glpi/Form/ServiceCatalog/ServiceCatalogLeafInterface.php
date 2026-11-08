<?php

namespace Glpi\Form\ServiceCatalog;

/**
 * Represent a leaf of the service catalog tree.
 * When the user click on this item (e.g. a form), he is redirected to the item
 * dedicated page.
 */
interface ServiceCatalogLeafInterface extends ServiceCatalogItemInterface
{
    /**
     * Get the URL to the target page represented by this leaf.
     * The url must be prefixed by the root doc.
     *
     * @used-by templates/pages/helpdesk/search.html.twig
     */
    public function getServiceCatalogLink(): string;
}

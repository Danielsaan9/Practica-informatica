<?php

namespace Glpi\Form\ServiceCatalog\Provider;

use Glpi\Form\ServiceCatalog\ItemRequest;

/**
 * @template T of \Glpi\Form\ServiceCatalog\ServiceCatalogItemInterface
 */
interface ItemProviderInterface
{
    /** @return T[] */
    public function getItems(ItemRequest $item_request): array;
}

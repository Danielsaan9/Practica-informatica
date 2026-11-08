<?php

namespace Glpi\Form\ServiceCatalog\Provider;

use Glpi\Form\ServiceCatalog\ItemRequest;

/**
 * @template T of \Glpi\Form\ServiceCatalog\ServiceCatalogLeafInterface
 * @extends ItemProviderInterface<T>
 */
interface LeafProviderInterface extends ItemProviderInterface
{
    /** @return T[] */
    public function getItems(ItemRequest $item_request): array;

    public function getItemsLabel(): string;

    public function getWeight(): int;
}

<?php

namespace Glpi\Form\ServiceCatalog;

use Glpi\Form\ServiceCatalog\Provider\FormProvider;
use Glpi\Form\ServiceCatalog\Provider\KnowbaseItemProvider;
use Glpi\Form\ServiceCatalog\Provider\LeafProviderInterface;
use Glpi\Toolbox\SingletonTrait;

final class HomeSearchManager
{
    use SingletonTrait;

    /** @var int */
    private const MAX_ITEMS_PER_TYPE = 20;

    /** @var LeafProviderInterface<covariant ServiceCatalogLeafInterface>[] */
    private array $providers;

    private bool $providers_are_sorted = false;

    public function __construct()
    {
        $this->providers = [
            FormProvider::getInstance(),
            new KnowbaseItemProvider(),
        ];
    }

    /** @return array<string, ServiceCatalogLeafInterface[]> */
    public function getItems(ItemRequest $item_request): array
    {
        $item_request->context = ItemRequestContext::HOME_PAGE_SEARCH;
        $items_by_label = [];

        foreach ($this->getProviders() as $leaf_provider) {
            $items = $leaf_provider->getItems($item_request);
            if ($items === []) {
                // Skip empty data
                continue;
            }

            // Limit result size
            $items = array_slice($items, 0, self::MAX_ITEMS_PER_TYPE);

            // Add items to results
            $items_by_label[$leaf_provider->getItemsLabel()] = $items;
        }

        return $items_by_label;
    }

    /**
     * @param LeafProviderInterface<covariant ServiceCatalogLeafInterface> $provider
     * @return void
     */
    public function registerPluginProvider(
        LeafProviderInterface $provider
    ): void {
        $this->providers[] = $provider;
        $this->providers_are_sorted = false;
    }

    /** @return LeafProviderInterface<covariant ServiceCatalogLeafInterface>[] */
    private function getProviders(): array
    {
        if (!$this->providers_are_sorted) {
            $this->sortProviders();
        }

        return $this->providers;
    }

    private function sortProviders(): void
    {
        usort(
            $this->providers,
            fn(
                LeafProviderInterface $a,
                LeafProviderInterface $b,
            ): int => $a->getWeight() <=> $b->getWeight()
        );
        $this->providers_are_sorted = true;
    }
}

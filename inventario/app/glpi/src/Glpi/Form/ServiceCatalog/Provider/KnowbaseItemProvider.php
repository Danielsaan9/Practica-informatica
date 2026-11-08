<?php

namespace Glpi\Form\ServiceCatalog\Provider;

use Glpi\DBAL\QueryExpression;
use Glpi\Form\ServiceCatalog\ItemRequest;
use Glpi\Form\ServiceCatalog\ItemRequestContext;
use Glpi\FuzzyMatcher\FuzzyMatcher;
use Glpi\FuzzyMatcher\PartialMatchStrategy;
use KnowbaseItem;
use Override;

/** @implements LeafProviderInterface<KnowbaseItem> */
final class KnowbaseItemProvider implements LeafProviderInterface
{
    private FuzzyMatcher $matcher;

    public function __construct()
    {
        $this->matcher = new FuzzyMatcher(new PartialMatchStrategy());
    }

    #[Override]
    public function getItems(ItemRequest $item_request): array
    {
        if (!KnowbaseItem::canView()) {
            return [];
        }

        $category_id = $item_request->getCategoryID();
        $filter = $item_request->getFilter();

        $knowbase_items = [];

        $criteria = [new QueryExpression('true')];
        if ($category_id !== null) {
            $criteria['forms_categories_id'] = $category_id;
        }

        // On the home page we want to search for all KB items even if they are
        // not enabled for the service catalog itself.
        if ($item_request->getContext() !== ItemRequestContext::HOME_PAGE_SEARCH) {
            $criteria['show_in_service_catalog'] = true;
        }
        // The root article is the FAQ home page, not content to offer.
        if (KnowbaseItem::hasRoot()) {
            $criteria[] = ['NOT' => ['id' => KnowbaseItem::getRootId()]];
        }

        $raw_knowbase_items = (new KnowbaseItem())->find($criteria, ['name']);

        foreach ($raw_knowbase_items as $raw_knowbase_item) {
            $knowbase_item = new KnowbaseItem();
            $knowbase_item->getFromResultSet($raw_knowbase_item);
            $knowbase_item->post_getFromDB();

            // Fuzzy matching
            $name        = $knowbase_item->fields['name'] ?? "";
            $answer      = $knowbase_item->fields['answer'] ?? "";
            $description = $knowbase_item->fields['description'] ?? "";
            if (
                !$this->matcher->match($name, $filter)
                && !$this->matcher->match($answer, $filter)
                && !$this->matcher->match($description, $filter)
            ) {
                continue;
            }

            // Checked per item, not in SQL: the number of KB items stays low.
            if (!$knowbase_item->can($knowbase_item->getID(), READ)) {
                continue;
            }

            $knowbase_items[] = $knowbase_item;
        }

        return $knowbase_items;
    }

    #[Override]
    public function getItemsLabel(): string
    {
        return __("FAQ articles");
    }

    #[Override]
    public function getWeight(): int
    {
        return 20;
    }
}

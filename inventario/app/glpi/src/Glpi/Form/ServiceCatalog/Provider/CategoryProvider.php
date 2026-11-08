<?php

namespace Glpi\Form\ServiceCatalog\Provider;

use Glpi\DBAL\QueryExpression;
use Glpi\Form\Category;
use Glpi\Form\ServiceCatalog\ItemRequest;
use Glpi\FuzzyMatcher\FuzzyMatcher;
use Glpi\FuzzyMatcher\PartialMatchStrategy;
use Override;

/** @implements CompositeProviderInterface<Category> */
final class CategoryProvider implements CompositeProviderInterface
{
    private FuzzyMatcher $matcher;

    public function __construct()
    {
        $this->matcher = new FuzzyMatcher(new PartialMatchStrategy());
    }

    #[Override]
    public function getItems(ItemRequest $item_request): array
    {
        $category_id = $item_request->getCategoryID();
        $filter = $item_request->getFilter();

        $category_restriction = [new QueryExpression('true')];
        if ($category_id !== null) {
            $category_restriction = [
                'forms_categories_id' => $category_id,
            ];
        }

        $categories = [];
        $raw_categories = (new Category())->find($category_restriction, ['name']);

        foreach ($raw_categories as $raw_category) {
            $category = new Category();
            $category->getFromResultSet($raw_category);
            $category->post_getFromDB();

            // Fuzzy matching
            $name = $category->fields['name'] ?? "";
            $description = $category->fields['description'] ?? "";
            if (
                !$this->matcher->match($name, $filter)
                && !$this->matcher->match($description, $filter)
            ) {
                continue;
            }

            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * @param ItemRequest $item_request
     * @return array<array{id: int, name: string}>
     */
    public function getAncestors(ItemRequest $item_request): array
    {
        $category_id = $item_request->getCategoryID();
        $category = Category::getById($category_id);
        if (!$category) {
            return [];
        }

        $categories = [];
        $current_category = [
            'id' => $category->getID(),
            'name' => $category->getServiceCatalogItemTitle(),
        ];

        /** @var Category $ancestor */
        foreach ($category->getAncestors() as $ancestor) {
            $categories[] = [
                'id' => $ancestor->getID(),
                'name' => $ancestor->getServiceCatalogItemTitle(),
            ];
        }

        if (!in_array($current_category['id'], array_column($categories, 'id'), true)) {
            $categories[] = $current_category;
        }

        return $categories;
    }
}

<?php

namespace Glpi\Form\ServiceCatalog\Provider;

use Glpi\Form\AccessControl\FormAccessControlManager;
use Glpi\Form\Form;
use Glpi\Form\ServiceCatalog\ItemRequest;
use Glpi\FuzzyMatcher\FuzzyMatcher;
use Glpi\FuzzyMatcher\PartialMatchStrategy;
use Glpi\Toolbox\SingletonTrait;
use Override;
use Session;

/** @implements LeafProviderInterface<Form> */
final class FormProvider implements LeafProviderInterface
{
    use SingletonTrait;

    private FormAccessControlManager $access_manager;
    private FuzzyMatcher $matcher;
    private array $entity_restriction_cache = [];

    private function __construct()
    {
        $this->access_manager = FormAccessControlManager::getInstance();
        $this->matcher = new FuzzyMatcher(new PartialMatchStrategy());
    }

    /**
     * Get entity restriction criteria with caching
     *
     * @param string $table The table name
     * @param mixed $value The entities values
     * @param bool $is_recursive Whether recursive entities are included
     * @return array The entity restriction criteria
     */
    private function getCachedEntityRestriction(string $table, mixed $value, bool $is_recursive): array
    {
        $cache_key = md5(serialize([
            'table' => $table,
            'value' => $value,
            'is_recursive' => $is_recursive,
        ]));

        if (!isset($this->entity_restriction_cache[$cache_key])) {
            $this->entity_restriction_cache[$cache_key] = getEntitiesRestrictCriteria(
                table: $table,
                value: $value,
                is_recursive: $is_recursive,
            );
        }

        return $this->entity_restriction_cache[$cache_key];
    }

    #[Override]
    public function getItems(ItemRequest $item_request): array
    {
        $category_id = $item_request->getCategoryID();
        $filter = $item_request->getFilter();
        $parameters = $item_request->getFormAccessParameters();

        $category_restriction = [];
        if ($category_id !== null) {
            $category_restriction = [
                'forms_categories_id' => $category_id,
            ];
        }

        $entity_restriction = $this->getCachedEntityRestriction(
            table: Form::getTable(),
            value: $parameters->getSessionInfo()->getActiveEntitiesIds(),
            is_recursive: true,
        );

        $forms = [];
        $raw_forms = (new Form())->find([
            'is_active'           => 1,
            'is_deleted'          => 0,
        ] + $category_restriction + $entity_restriction, ['name']);

        foreach ($raw_forms as $raw_form) {
            $form = new Form();
            $form->getFromResultSet($raw_form);
            $form->post_getFromDB();

            // Fuzzy matching
            $name = $form->fields['name'] ?? "";
            $description = $form->fields['description'] ?? "";
            if (
                !$form->fields['is_pinned'] // Pinned forms are not filtered
                && !$this->matcher->match($name, $filter)
                && !$this->matcher->match($description, $filter)
            ) {
                continue;
            }

            /// Note: this is in theory less performant than applying the parameters
            // directly to the SQL query (which would require more complicated code).
            // However, the number of forms is expected to be low, so this is acceptable.
            // If performance becomes an issue, we can revisit this and/or add a cache.
            if (!$this->access_manager->canAnswerForm($form, $parameters)) {
                continue;
            }

            $forms[] = $form;
        }

        return $forms;
    }

    #[Override]
    public function getItemsLabel(): string
    {
        return Form::getTypeName(Session::getPluralNumber());
    }

    #[Override]
    public function getWeight(): int
    {
        return 10;
    }
}

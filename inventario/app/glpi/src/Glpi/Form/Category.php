<?php

namespace Glpi\Form;

use CommonTreeDropdown;
use DropdownTranslation;
use Glpi\Form\ServiceCatalog\ItemRequest;
use Glpi\Form\ServiceCatalog\ServiceCatalogCompositeInterface;
use Glpi\Form\ServiceCatalog\ServiceCatalogItemInterface;
use Glpi\UI\IllustrationManager;
use Override;

class Category extends CommonTreeDropdown implements ServiceCatalogCompositeInterface
{
    public bool $can_be_translated = true;

    public static string $rightname = 'form';

    /** @var ServiceCatalogItemInterface[] $children */
    private array $children = [];

    #[Override]
    public static function getTypeName($nb = 0): string
    {
        return _n('Service catalog category', 'Service catalog categories', $nb);
    }

    #[Override]
    public static function getIcon(): string
    {
        return "ti ti-tags";
    }

    #[Override]
    protected function insertTabs($options = []): array
    {
        $tabs = [];
        $this->addStandardTab(Form::class, $tabs, $options);

        return $tabs;
    }

    #[Override]
    public function getAdditionalFields()
    {
        $fields = parent::getAdditionalFields();
        $fields[] = [
            'name'        => 'description',
            'label'       => __('Description'),
            'type'        => 'tinymce',
            'form_params' => ['enable_images' => false, 'full_width' => false],
            'list'        => false,
        ];
        $fields[] = [
            'name'  => 'illustration',
            'label' => __('Illustration'),
            'type'  => 'illustration',
            'list'  => false,
        ];

        return $fields;
    }

    #[Override]
    public function rawSearchOptions(): array
    {
        $options = parent::rawSearchOptions();
        $options[] = [
            'id'                => '3',
            'table'             => $this->getTable(),
            'field'             => 'description',
            'name'              => __('Description'),
            'datatype'          => 'text',
        ];

        $options[] = [
            'id'                => '4',
            'table'             => $this->getTable(),
            'field'             => 'illustration',
            'name'              => __('Illustration'),
            'massiveaction'      => false,
            'nosearch'          => true,
            'datatype'          => 'specific',
        ];

        return $options;
    }

    public static function getSpecificValueToDisplay($field, $values, array $options = [])
    {
        if (!is_array($values)) {
            $values = [$field => $values];
        }

        switch ($field) {
            case 'illustration':
                $illustration_manager = new IllustrationManager();
                $value = $values[$field];
                $icon = $illustration_manager->renderIcon($value, 32);

                // Only native illustrations have a title.
                $is_custom_file = str_starts_with($value, IllustrationManager::CUSTOM_ILLUSTRATION_PREFIX);
                $title = (!$is_custom_file && $value !== '') ? $illustration_manager->getIconTitle($value) : '';
                if ($title === '') {
                    return $icon;
                }

                // The icon is aria-hidden by design; name the cell here instead.
                return '<span role="img" aria-label="' . htmlescape($title) . '">' . $icon . '</span>';
        }
        return parent::getSpecificValueToDisplay($field, $values, $options);
    }

    #[Override]
    public function getServiceCatalogItemTitle(): string
    {
        return DropdownTranslation::getTranslatedValue(
            $this->fields['id'],
            self::class,
            'name',
            value: $this->fields['name'],
        );
    }

    #[Override]
    public function getServiceCatalogItemDescription(): string
    {
        return DropdownTranslation::getTranslatedValue(
            $this->fields['id'],
            self::class,
            'description',
            value: $this->fields['description'] ?? ''
        );
    }

    #[Override]
    public function getServiceCatalogItemIllustration(): string
    {
        return $this->fields['illustration'] ?: IllustrationManager::DEFAULT_ILLUSTRATION;
    }

    #[Override]
    public function isServiceCatalogItemPinned(): bool
    {
        return false;
    }

    #[Override]
    public function getChildrenUrlParameters(): string
    {
        return http_build_query(['category' => $this->getID()]);
    }

    #[Override]
    public function getChildrenItemRequest(
        ItemRequest $item_request,
    ): ItemRequest {
        return new ItemRequest(
            access_parameters: $item_request->getFormAccessParameters(),
            category_id: $this->getID(),
        );
    }

    #[Override]
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    #[Override]
    public function getChildren(): array
    {
        return $this->children;
    }
}

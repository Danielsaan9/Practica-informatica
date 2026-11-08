<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\Destination\HasFormTags;
use Glpi\Form\Migration\TagConversionTrait;
use Override;

#[HasFormTags]
final class ControlsListField extends AbstractTextAreaField
{
    use TagConversionTrait;

    #[Override]
    protected function getColumnName(): string
    {
        return 'controlistcontent';
    }

    #[Override]
    public function getLabel(): string
    {
        return __("Control list");
    }

    #[Override]
    public function getCategory(): Category
    {
        return Category::ANALYSIS;
    }

    #[Override]
    public function getWeight(): int
    {
        return 20;
    }
}

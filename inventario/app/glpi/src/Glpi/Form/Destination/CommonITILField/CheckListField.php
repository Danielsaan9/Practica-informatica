<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\Destination\HasFormTags;
use Glpi\Form\Migration\TagConversionTrait;
use Override;

#[HasFormTags]
final class CheckListField extends AbstractTextAreaField
{
    use TagConversionTrait;

    #[Override]
    protected function getColumnName(): string
    {
        return 'checklistcontent';
    }

    #[Override]
    public function getLabel(): string
    {
        return __("Checklist");
    }

    #[Override]
    public function getCategory(): Category
    {
        return Category::PLANS;
    }

    #[Override]
    public function getWeight(): int
    {
        return 30;
    }
}

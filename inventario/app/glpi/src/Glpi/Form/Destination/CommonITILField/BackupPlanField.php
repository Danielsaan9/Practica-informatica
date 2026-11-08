<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\Destination\HasFormTags;
use Glpi\Form\Migration\TagConversionTrait;
use Override;

#[HasFormTags]
final class BackupPlanField extends AbstractTextAreaField
{
    use TagConversionTrait;

    #[Override]
    protected function getColumnName(): string
    {
        return 'backoutplancontent';
    }

    #[Override]
    public function getLabel(): string
    {
        return __("Backup plan");
    }

    #[Override]
    public function getCategory(): Category
    {
        return Category::PLANS;
    }

    #[Override]
    public function getWeight(): int
    {
        return 20;
    }
}

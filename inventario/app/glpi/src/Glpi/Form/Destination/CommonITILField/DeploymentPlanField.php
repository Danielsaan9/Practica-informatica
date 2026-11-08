<?php

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\Form\Destination\HasFormTags;
use Glpi\Form\Migration\TagConversionTrait;
use Override;

#[HasFormTags]
final class DeploymentPlanField extends AbstractTextAreaField
{
    use TagConversionTrait;

    #[Override]
    protected function getColumnName(): string
    {
        return 'rolloutplancontent';
    }

    #[Override]
    public function getLabel(): string
    {
        return __("Deployment plan");
    }

    #[Override]
    public function getCategory(): Category
    {
        return Category::PLANS;
    }

    #[Override]
    public function getWeight(): int
    {
        return 10;
    }
}

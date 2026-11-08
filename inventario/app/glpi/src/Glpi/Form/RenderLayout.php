<?php

namespace Glpi\Form;

enum RenderLayout: string
{
    case STEP_BY_STEP = 'step_by_step';
    case SINGLE_PAGE  = 'single_page';

    public function getLabel(): string
    {
        return match ($this) {
            self::STEP_BY_STEP => __('Section by section'),
            self::SINGLE_PAGE  => __('Single page'),
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::STEP_BY_STEP => 'ti ti-layout-distribute-vertical',
            self::SINGLE_PAGE  => 'ti ti-layout-distribute-horizontal',
        };
    }
}

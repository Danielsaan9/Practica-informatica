<?php

class RuleChange extends RuleCommonITILObject
{
    // From Rule
    public static string $rightname = 'rule_change';

    public function getTitle()
    {
        return __('Business rules for changes');
    }

    #[Override]
    public function getTargetItilType(): Change
    {
        return new Change();
    }
}

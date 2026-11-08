<?php

class RuleProblem extends RuleCommonITILObject
{
    // From Rule
    public static string $rightname = 'rule_problem';

    public function getTitle()
    {
        return __('Business rules for problems');
    }

    #[Override]
    public function getTargetItilType(): Problem
    {
        return new Problem();
    }
}

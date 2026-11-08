<?php

final class ChangeValidationStep extends ITIL_ValidationStep
{
    public static string $rightname = 'changevalidation';
    public static string $validation_classname = ChangeValidation::class;

    public static function getTypeName($nb = 0)
    {
        return _n('Change Approval step', 'Change Approval steps', $nb);
    }
}

<?php

class RecurrentChange extends CommonITILRecurrent
{
    /**
     * @var string Right managements
     */
    public static string $rightname = 'recurrentchange';

    public static function getTypeName($nb = 0)
    {
        return __('Recurrent changes');
    }

    public static function getSectorizedDetails(): array
    {
        return ['helpdesk', self::class];
    }

    public static function getConcreteClass()
    {
        return Change::class;
    }

    public static function getTemplateClass()
    {
        return ChangeTemplate::class;
    }

    public static function getPredefinedFieldsClass()
    {
        return ChangeTemplatePredefinedField::class;
    }
}

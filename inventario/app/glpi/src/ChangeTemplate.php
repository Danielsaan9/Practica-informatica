<?php

use Glpi\Features\Clonable;

/**
 * Chang Template class
 *
 * @since 9.5.0
 **/
class ChangeTemplate extends ITILTemplate
{
    /** @use Clonable<static> */
    use Clonable;

    #[Override]
    public static function getPredefinedFields(): ITILTemplatePredefinedField
    {
        return new ChangeTemplatePredefinedField();
    }

    public static function getTypeName($nb = 0)
    {
        return _n('Change template', 'Change templates', $nb);
    }

    public static function getSectorizedDetails(): array
    {
        return ['helpdesk', Change::class, self::class];
    }

    public function getCloneRelations(): array
    {
        return [
            ChangeTemplateHiddenField::class,
            ChangeTemplateMandatoryField::class,
            ChangeTemplatePredefinedField::class,
            ChangeTemplateReadonlyField::class,
        ];
    }

    public function cleanDBonPurge()
    {
        $this->deleteChildrenAndRelationsFromDb(
            [
                ChangeTemplateHiddenField::class,
                ChangeTemplateMandatoryField::class,
                ChangeTemplatePredefinedField::class,
            ]
        );
    }

    public static function getExtraAllowedFields($withtypeandcategory = false, $withitemtype = false)
    {
        $change = new Change();
        return [
            $change->getSearchOptionIDByField('field', 'time_to_resolve', 'glpi_changes') => 'time_to_resolve',
            $change->getSearchOptionIDByField('field', 'impactcontent', 'glpi_changes')      => 'impactcontent',
            $change->getSearchOptionIDByField('field', 'controlistcontent', 'glpi_changes')  => 'controlistcontent',
            $change->getSearchOptionIDByField('field', 'rolloutplancontent', 'glpi_changes') => 'rolloutplancontent',
            $change->getSearchOptionIDByField('field', 'backoutplancontent', 'glpi_changes') => 'backoutplancontent',
            $change->getSearchOptionIDByField('field', 'checklistcontent', 'glpi_changes')   => 'checklistcontent',
        ];
    }
}

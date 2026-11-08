<?php

use Glpi\Features\Clonable;

/**
 * Problem template class
 *
 * since version 9.5.0
 **/
class ProblemTemplate extends ITILTemplate
{
    /** @use Clonable<static> */
    use Clonable;

    #[Override]
    public static function getPredefinedFields(): ITILTemplatePredefinedField
    {
        return new ProblemTemplatePredefinedField();
    }

    public static function getTypeName($nb = 0)
    {
        return _n('Problem template', 'Problem templates', $nb);
    }

    public static function getSectorizedDetails(): array
    {
        return ['helpdesk', Problem::class, self::class];
    }

    public function getCloneRelations(): array
    {
        return [
            ProblemTemplateHiddenField::class,
            ProblemTemplateMandatoryField::class,
            ProblemTemplatePredefinedField::class,
            ProblemTemplateReadonlyField::class,
        ];
    }

    public function cleanDBonPurge()
    {
        $this->deleteChildrenAndRelationsFromDb(
            [
                ProblemTemplateHiddenField::class,
                ProblemTemplateMandatoryField::class,
                ProblemTemplatePredefinedField::class,
            ]
        );
    }

    public static function getExtraAllowedFields($withtypeandcategory = false, $withitemtype = false)
    {
        $problem = new Problem();
        return [
            $problem->getSearchOptionIDByField('field', 'impactcontent', 'glpi_problems')  => 'impactcontent',
            $problem->getSearchOptionIDByField('field', 'causecontent', 'glpi_problems')   => 'causecontent',
            $problem->getSearchOptionIDByField('field', 'symptomcontent', 'glpi_problems') => 'symptomcontent',
        ];
    }
}

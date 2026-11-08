<?php

use Glpi\Features\Clonable;

/**
 * Ticket Template class
 *
 * since version 0.83
 **/
class TicketTemplate extends ITILTemplate
{
    /** @use Clonable<static> */
    use Clonable;

    #[Override]
    public static function getPredefinedFields(): ITILTemplatePredefinedField
    {
        return new TicketTemplatePredefinedField();
    }

    public static function getTypeName($nb = 0)
    {
        return _n('Ticket template', 'Ticket templates', $nb);
    }

    public static function getSectorizedDetails(): array
    {
        return ['helpdesk', Ticket::class, self::class];
    }

    public function getCloneRelations(): array
    {
        return [
            TicketTemplateHiddenField::class,
            TicketTemplateMandatoryField::class,
            TicketTemplatePredefinedField::class,
            TicketTemplateReadonlyField::class,
        ];
    }

    public function cleanDBonPurge()
    {
        $this->deleteChildrenAndRelationsFromDb(
            [
                TicketTemplateHiddenField::class,
                TicketTemplateMandatoryField::class,
                TicketTemplatePredefinedField::class,
            ]
        );
    }

    public static function getExtraAllowedFields($withtypeandcategory = false, $withitemtype = false)
    {
        $itil_object = new Ticket();
        $tab =  [
            $itil_object->getSearchOptionIDByField(
                'field',
                'name',
                'glpi_requesttypes'
            )
                                                       => 'requesttypes_id',
            $itil_object->getSearchOptionIDByField(
                'field',
                'slas_id_tto',
                'glpi_slas'
            )      => 'slas_id_tto',
            $itil_object->getSearchOptionIDByField(
                'field',
                'slas_id_ttr',
                'glpi_slas'
            )      => 'slas_id_ttr',

            // hardcoded, cannot use $itil_object->getSearchOptionIDByField()
            // since both search options share common specs
            190      => '_olas_id_tto',
            191      => '_olas_id_ttr',

            $itil_object->getSearchOptionIDByField(
                'field',
                'time_to_own',
                'glpi_tickets'
            )   => 'time_to_own',
            $itil_object->getSearchOptionIDByField(
                'field',
                'global_validation',
                'glpi_tickets'
            )   => 'global_validation',
            $itil_object->getSearchOptionIDByField(
                'field',
                'name',
                'glpi_contracts'
            )   => '_contracts_id',
            $itil_object->getSearchOptionIDByField(
                'field',
                'type',
                'glpi_tickets'
            )   => 'type',
            $itil_object->getSearchOptionIDByField(
                'field',
                'externalid',
                'glpi_tickets'
            )   => 'externalid',
        ];

        if ($withtypeandcategory) {
            $tab[$itil_object->getSearchOptionIDByField(
                'field',
                'type',
                $itil_object->getTable()
            )]         = 'type';
        }

        return $tab;
    }
}

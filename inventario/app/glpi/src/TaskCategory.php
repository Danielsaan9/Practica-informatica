<?php

use Glpi\Features\Clonable;

/**
 * TaskCategory class
 **/
class TaskCategory extends CommonTreeDropdown
{
    /** @use Clonable<static> */
    use Clonable;

    // From CommonDBTM
    public bool $dohistory          = true;
    public bool $can_be_translated  = true;

    public static string $rightname          = 'taskcategory';

    public function getAdditionalFields()
    {

        $tab = parent::getAdditionalFields();

        $tab[] = ['name'  => 'is_active',
            'label' => __('Active'),
            'type'  => 'bool',
        ];

        $tab[] = ['name'  => 'knowbaseitems_id',
            'label' => KnowbaseItem::getTypeName(),
            'type'  => 'dropdownValue',
            'list'  => true,
        ];

        return $tab;
    }


    public function rawSearchOptions()
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '8',
            'table'              => $this->getTable(),
            'field'              => 'is_active',
            'name'               => __('Active'),
            'datatype'           => 'bool',
        ];

        $tab[] = [
            'id'                 => '9',
            'table'              => 'glpi_knowbaseitems',
            'field'              => 'name',
            'linkfield'          => 'knowbaseitems_id',
            'name'               => __('Knowledge base'),
            'datatype'           => 'dropdown',
        ];

        return $tab;
    }


    public static function getTypeName($nb = 0)
    {
        return _n('Task category', 'Task categories', $nb);
    }

    public static function getIcon()
    {
        return "ti ti-tags";
    }

    public function getCloneRelations(): array
    {
        return [];
    }
}

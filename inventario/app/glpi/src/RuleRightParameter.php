<?php

/// LDAP criteria class
class RuleRightParameter extends CommonDropdown
{
    public static string $rightname         = 'rule_ldap';

    public bool $can_be_translated = false;


    /**
     * @see CommonDBTM::prepareInputForAdd()
     **/
    public function prepareInputForAdd($input)
    {
        //LDAP parameters MUST be in lower case
        //because they are retrieved in lower case  from the directory
        $input["value"] = Toolbox::strtolower($input["value"]);
        return $input;
    }

    public function getAdditionalFields()
    {

        return [
            [
                'name'  => 'value',
                'label' => _n('Criterion', 'Criteria', 1),
                'type'  => 'text',
                'list'  => false,
            ],
        ];
    }

    public function rawSearchOptions()
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '11',
            'table'              => $this->getTable(),
            'field'              => 'value',
            'name'               => _n('Criterion', 'Criteria', 1),
            'datatype'           => 'string',
        ];

        return $tab;
    }

    public static function getTypeName($nb = 0)
    {
        return _n('LDAP criterion', 'LDAP criteria', $nb);
    }
}

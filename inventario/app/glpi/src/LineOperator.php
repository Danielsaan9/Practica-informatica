<?php

/**
 * @since 9.2
 */


class LineOperator extends CommonDropdown
{
    public static string $rightname = 'lineoperator';

    public bool $can_be_translated = false;

    public static function getTypeName($nb = 0)
    {
        return _n('Line operator', 'Line operators', $nb);
    }

    public function getAdditionalFields()
    {
        return [['name'  => 'mcc',
            'label' => __('Mobile Country Code'),
            'type'  => 'integer',
            'list'  => true,
        ],
            ['name'  => 'mnc',
                'label' => __('Mobile Network Code'),
                'type'  => 'integer',
                'list'  => true,
            ],
        ];
    }

    public function rawSearchOptions()
    {
        $tab = parent::rawSearchOptions();

        $tab[] = [
            'id'                 => '11',
            'table'              => $this->getTable(),
            'field'              => 'mcc',
            'name'               => __('Mobile Country Code'),
            'datatype'           => 'integer',
        ];

        $tab[] = [
            'id'                 => '12',
            'table'              => $this->getTable(),
            'field'              => 'mnc',
            'name'               => __('Mobile Network Code'),
            'datatype'           => 'integer',
        ];

        return $tab;
    }

    public function prepareInputForAdd($input)
    {
        global $DB;

        $input = parent::prepareInputForAdd($input);

        if (!isset($input['mcc'])) {
            $input['mcc'] = 0;
        }
        if (!isset($input['mnc'])) {
            $input['mnc'] = 0;
        }

        //check for mcc/mnc unicity
        $result = $DB->request([
            'COUNT'  => 'cpt',
            'FROM'   => self::getTable(),
            'WHERE'  => [
                'mcc' => $input['mcc'],
                'mnc' => $input['mnc'],
            ],
        ])->current();

        if ($result['cpt'] > 0) {
            Session::addMessageAfterRedirect(
                __s('Mobile country code and network code combination must be unique!'),
                false,
                ERROR
            );
            return false;
        }

        return $input;
    }
}

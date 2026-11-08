<?php

class DomainRelation extends CommonDropdown
{
    public const BELONGS = 1;
    public const MANAGE = 2;
    // From CommonDBTM
    public bool $dohistory                   = true;
    public static string $rightname                   = 'dropdown';

    public static array $knowrelations = [
        [
            'id'        => self::BELONGS,
            'name'      => 'Belongs',
            'comment'   => 'Item belongs to domain',
        ], [
            'id'        => self::MANAGE,
            'name'      => 'Manage',
            'comment'   => 'Item manages domain',
        ],
    ];

    public static function getTypeName($nb = 0)
    {
        return _n('Domain relation', 'Domains relations', $nb);
    }

    public function defineTabs($options = [])
    {

        $ong = [];
        $this->addDefaultFormTab($ong);
        $this->addStandardTab(Domain_Item::class, $ong, $options);
        $this->addStandardTab(Log::class, $ong, $options);

        return $ong;
    }

    /**
     * @return array
     */
    public static function getDefaults()
    {
        return array_map(
            function ($e) {
                $e['is_recursive'] = 1;
                return $e;
            },
            self::$knowrelations
        );
    }

    public function pre_deleteItem()
    {
        if (in_array($this->fields['id'], [self::BELONGS, self::MANAGE])) {
            //keep defaults
            return false;
        }
        return true;
    }
}

<?php

/**
 * @since 9.2
 */


/**
 * OlaLevel class
 **/
class OlaLevel extends LevelAgreementLevel
{
    protected string $rules_id_field     = 'olalevels_id';
    protected string $ruleactionclass    = 'OlaLevelAction';
    protected static string $parentclass = 'OLA';
    protected static string $fkparent    = 'olas_id';
    // No criteria
    protected string $rulecriteriaclass = 'OlaLevelCriteria';


    public static function getTable($classname = null)
    {
        return CommonDBTM::getTable(self::class);
    }

    public static function getSectorizedDetails(): array
    {
        return ['config', OLA::class, self::class];
    }

    public function cleanDBonPurge()
    {
        parent::cleanDBonPurge();

        // OlaLevel_Ticket does not extends CommonDBConnexity
        $olt = new OlaLevel_Ticket();
        $olt->deleteByCriteria([$this->rules_id_field => $this->fields['id']]);
    }

    #[Override]
    public function showForParent(LevelAgreement $la)
    {
        $this->showForLA($la);
    }

    public function getActions()
    {
        $actions = parent::getActions();

        unset($actions['olas_id']);
        $actions['recall_ola']['name']          = __('Automatic reminders of OLA');
        $actions['recall_ola']['type']          = 'yesonly';
        $actions['recall_ola']['force_actions'] = ['send'];

        return $actions;
    }

    /**
     * Get first level for a OLA
     *
     * @param int $olas_id id of the OLA
     *
     * @since 9.1 (before getFirst OlaLevel)
     *
     * @return int id of the ola level : 0 if not exists
     **/
    public static function getFirstOlaLevel($olas_id)
    {
        global $DB;

        $iterator = $DB->request([
            'SELECT' => 'id',
            'FROM'   => 'glpi_olalevels',
            'WHERE'  => [
                'olas_id'   => $olas_id,
                'is_active' => 1,
            ],
            'ORDER'  => 'execution_time ASC',
            'LIMIT'  => 1,
        ]);

        if (count($iterator)) {
            $result = $iterator->current();
            return $result['id'];
        }
        return 0;
    }

    /**
     * Get next level for a OLA
     *
     * @param int $olas_id      id of the OLA
     * @param int $olalevels_id id of the current OLA level
     *
     * @return int id of the ola level : 0 if not exists
     **/
    public static function getNextOlaLevel($olas_id, $olalevels_id)
    {
        global $DB;

        $iterator = $DB->request([
            'SELECT' => 'execution_time',
            'FROM'   => 'glpi_olalevels',
            'WHERE'  => ['id' => $olalevels_id],
        ]);

        if (count($iterator)) {
            $result = $iterator->current();
            $execution_time = $result['execution_time'];

            $iterator = $DB->request([
                'SELECT' => 'id',
                'FROM'   => 'glpi_olalevels',
                'WHERE'  => [
                    'olas_id'         => $olas_id,
                    'id'              => ['<>', $olalevels_id],
                    'execution_time'  => ['>', $execution_time],
                    'is_active'       => 1,
                ],
                'ORDER'  => 'execution_time ASC',
                'LIMIT'  => 1,
            ]);

            if (count($iterator)) {
                $result = $iterator->current();
                return $result['id'];
            }
        }
        return 0;
    }
}

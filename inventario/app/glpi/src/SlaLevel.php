<?php

/**
 * SlaLevel class
 **/
class SlaLevel extends LevelAgreementLevel
{
    protected string $rules_id_field     = 'slalevels_id';
    protected string $ruleactionclass    = SlaLevelAction::class;
    protected static string $parentclass = SLA::class;
    protected static string $fkparent    = 'slas_id';
    // No criteria
    protected string $rulecriteriaclass = SlaLevelCriteria::class;

    public static function getTable($classname = null)
    {
        return CommonDBTM::getTable(self::class);
    }

    public static function getSectorizedDetails(): array
    {
        return ['config', SLA::class, self::class];
    }

    public function cleanDBonPurge()
    {
        parent::cleanDBonPurge();

        // SlaLevel_Ticket does not extends CommonDBConnexity
        $slt = new SlaLevel_Ticket();
        $slt->deleteByCriteria([$this->rules_id_field => $this->fields['id']]);
    }

    #[Override]
    public function showForParent(LevelAgreement $la)
    {
        $this->showForLA($la);
    }

    public function getForbiddenStandardMassiveAction()
    {
        $forbidden   = parent::getForbiddenStandardMassiveAction();
        $forbidden[] = 'clone';
        return $forbidden;
    }

    public function getActions()
    {
        $actions = parent::getActions();

        unset($actions['slas_id']);
        $actions['recall']['name']          = __('Automatic reminders of SLA');
        $actions['recall']['type']          = 'yesonly';
        $actions['recall']['force_actions'] = ['send'];

        return $actions;
    }

    /**
     * Get first level for a SLA
     *
     * @param int $slas_id id of the SLA
     *
     * @since 9.1 (before getFirst SlaLevel)
     *
     * @return int id of the sla level : 0 if not exists
     **/
    public static function getFirstSlaLevel($slas_id)
    {
        global $DB;

        $iterator = $DB->request([
            'SELECT' => 'id',
            'FROM'   => self::getTable(),
            'WHERE'  => [
                'slas_id'   => $slas_id,
                'is_active' => 1,
            ],
            'ORDER'  => 'execution_time ASC',
            'LIMIT'  => 1,
        ]);

        if ($result = $iterator->current()) {
            return $result['id'];
        }
        return 0;
    }

    /**
     * Get next level for a SLA
     *
     * @param int $slas_id      id of the SLA
     * @param int $slalevels_id id of the current SLA level
     *
     * @return int id of the sla level : 0 if not exists
     **/
    public static function getNextSlaLevel($slas_id, $slalevels_id)
    {
        global $DB;

        $iterator = $DB->request([
            'SELECT' => 'execution_time',
            'FROM'   => self::getTable(),
            'WHERE'  => ['id' => $slalevels_id],
        ]);

        if ($result = $iterator->current()) {
            $execution_time = $result['execution_time'];

            $lvl_iterator = $DB->request([
                'SELECT' => 'id',
                'FROM'   => self::getTable(),
                'WHERE'  => [
                    'slas_id'         => $slas_id,
                    'is_active'       => 1,
                    'id'              => ['<>', $slalevels_id],
                    'execution_time'  => ['>', $execution_time],
                ],
                'ORDER'  => 'execution_time ASC',
                'LIMIT'  => 1,
            ]);

            if ($result = $lvl_iterator->current()) {
                return $result['id'];
            }
        }
        return 0;
    }
}

<?php

use Glpi\DBAL\QueryExpression;

/**
 * CronTaskLog class
 **/
class CronTaskLog extends CommonDBChild
{
    public static string $itemtype = CronTask::class;
    public static string $items_id  = 'crontasks_id';

    // Prevent CronTaskLog entries from flooding the CronTask historical tab
    public static bool $logs_for_parent = false;

    // Class constant
    public const STATE_START = 0;
    public const STATE_RUN   = 1;
    public const STATE_STOP  = 2;
    public const STATE_ERROR = 3;

    public static function getIcon()
    {
        return "ti ti-news";
    }

    /**
     * Clean old event for a task
     *
     * @param int $id   ID of the CronTask
     * @param int $days number of day to keep
     *
     * @return int number of events deleted
     **/
    public static function cleanOld($id, $days)
    {
        global $DB;

        $secs      = $days * DAY_TIMESTAMP;

        $result = $DB->delete(
            'glpi_crontasklogs',
            [
                'crontasks_id' => $id,
                new QueryExpression("UNIX_TIMESTAMP(" . $DB->quoteName("date") . ") < UNIX_TIMESTAMP()-$secs"),
            ]
        );

        return $result ? $DB->getAffectedRows() : 0;
    }


    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {

        if (!$withtemplate) {
            $nb = 0;
            if ($item instanceof CronTask) {
                $ong    = [];
                $ong[1] = self::createTabEntry(__('Statistics'), 0, $item::class, 'ti ti-report-analytics');
                if ($_SESSION['glpishow_count_on_tabs']) {
                    $nb =  countElementsInTable(
                        $this->getTable(),
                        ['crontasks_id' => $item->getID(),
                            'state'        => self::STATE_STOP,
                        ]
                    );
                }
                $ong[2] = self::createTabEntry(_n('Log', 'Logs', Session::getPluralNumber()), $nb, $item::class);
                return $ong;
            }
        }
        return '';
    }


    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {

        if ($item instanceof  CronTask) {
            switch ($tabnum) {
                case 1:
                    $item->showStatistics();
                    break;

                case 2:
                    $item->showHistory();
                    break;
            }
        }
        return true;
    }
}

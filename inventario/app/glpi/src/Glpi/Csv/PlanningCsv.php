<?php

namespace Glpi\Csv;

use DateTimeZone;
use Safe\DateTime;
use User;

/**
 * Planning CSV export Class
 **/
class PlanningCsv implements ExportToCsvInterface
{
    private int $users_id;
    private ?int $groups_id;
    private string $limititemtype;

    /**
     * @param int     $who            user ID
     * @param int     $whogroup       group ID
     * @param string  $limititemtype  itemtype only display this itemtype (default '')
     */
    public function __construct($who, $whogroup = null, $limititemtype = '')
    {
        $this->users_id      = $who;
        $this->groups_id     = $whogroup;
        $this->limititemtype = $limititemtype;
    }

    public function getFileName(): string
    {
        return "planning.csv";
    }

    public function getFileHeader(): array
    {
        return [
            __('Actor'),
            __('Title'),
            __('Item type'),
            __('Item id'),
            __('Begin date'),
            __('End date'),
        ];
    }

    public function getFileContent(): array
    {
        global $CFG_GLPI;

        $interv = [];
        $lines  = [];
        $begin  = time() - MONTH_TIMESTAMP * 12;
        $end    = time() + MONTH_TIMESTAMP * 12;
        $begin  = date("Y-m-d H:i:s", $begin);
        $end    = date("Y-m-d H:i:s", $end);
        $params = [
            'who'       => $this->users_id,
            'whogroup'  => $this->groups_id,
            'begin'     => $begin,
            'end'       => $end,
        ];

        if (empty($this->limititemtype)) {
            foreach ($CFG_GLPI['planning_types'] as $itemtype) {
                $interv = array_merge($interv, $itemtype::populatePlanning($params));
            }
        } else {
            $interv = $this->limititemtype::populatePlanning($params);
        }

        if (count($interv) > 0) {
            foreach ($interv as $val) {
                $dateBegin = new DateTime($val["begin"]);
                $dateBegin->setTimeZone(new DateTimeZone('UTC'));

                $dateEnd = new DateTime($val["end"]);
                $dateEnd->setTimeZone(new DateTimeZone('UTC'));

                $itemtype = getItemForItemtype($val['itemtype']);

                $user = new User();
                $user->getFromDB($val['users_id']);

                $lines[] = [
                    'actor'     => $user->getFriendlyName(),
                    'title'     => $val['name'],
                    'itemtype'  => $itemtype->getTypeName(1),
                    'items_id'  => $val[$itemtype->getForeignKeyField()],
                    'begindate' => $dateBegin->format('Y-m-d H:i:s'),
                    'enddate'   => $dateEnd->format('Y-m-d H:i:s'),
                ];
            }
        }

        return $lines;
    }
}

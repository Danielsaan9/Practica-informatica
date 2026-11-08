<?php

namespace Glpi\Dashboard;

use CommonDBChild;
use Glpi\DBAL\QueryParam;

class Right extends CommonDBChild
{
    public static string $itemtype = Dashboard::class;
    public static string $items_id = 'dashboards_dashboards_id';

    // prevent bad getFromDB when bootstraping tests suite
    // FIXME Should be true
    public static bool $mustBeAttached = false;

    /**
     * Return rights for the provided dashboard
     *
     * @param int $dashboards_id
     *
     * @return array the rights
     */
    public static function getForDashboard(int $dashboards_id = 0): array
    {
        global $DB;

        $dr_iterator = $DB->request([
            'FROM'  => self::getTable(),
            'WHERE' => [
                'dashboards_dashboards_id' => $dashboards_id,
            ],
        ]);

        $rights = [];
        foreach ($dr_iterator as $right) {
            unset($right['id']);
            $rights[] = $right;
        }

        return $rights;
    }


    /**
     * Save rights in DB for the provided dashboard
     *
     * @param int $dashboards_id id (not key) of the dashboard
     * @param array $rights contains these data:
     * - 'users_id'    => [items_id]
     * - 'groups_id'   => [items_id]
     * - 'entities_id' => [items_id]
     * - 'profiles_id' => [items_id]
     *
     * @return void
     */
    public static function addForDashboard(int $dashboards_id = 0, array $rights = [])
    {
        global $DB;

        $query_rights = $DB->buildInsert(
            self::getTable(),
            [
                'dashboards_dashboards_id' => new QueryParam(),
                'itemtype' => new QueryParam(),
                'items_id' => new QueryParam(),
            ]
        );
        $stmt = $DB->prepare($query_rights);
        foreach ($rights as $fk => $right_line) {
            $itemtype = getItemtypeForForeignKeyField($fk);
            foreach ($right_line as $items_id) {
                $DB->executeStatement(
                    $stmt,
                    [
                        $dashboards_id,
                        $itemtype,
                        $items_id,
                    ],
                    ['i', 's', 'i']
                );
            }
        }
    }
}

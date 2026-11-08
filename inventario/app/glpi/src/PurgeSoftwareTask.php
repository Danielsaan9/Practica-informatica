<?php

use Glpi\DBAL\QuerySubQuery;

class PurgeSoftwareTask
{
    public const TASK_NAME = 'purgesoftware';
    private const MAX_BATCH_SIZE = 2000;

    public function run(?int $max): int
    {
        $total = 0;
        $criteria = $this->getDeletedSoftwareWithNoVersionsCriteria();
        $software = new Software();
        $total += $this->purgeItems($criteria, $software, $max - $total);
        return $total;
    }

    protected function getDeletedSoftwareWithNoVersionsCriteria(): array
    {
        return [
            'SELECT' => 'id',
            'FROM'   => Software::getTable(),
            'WHERE'  => [
                'is_deleted' => 1,
                'NOT' => [
                    'id' => new QuerySubQuery(
                        [
                            'SELECT' => 'softwares_id',
                            'FROM'   => SoftwareVersion::getTable(),
                        ]
                    ),
                ],
            ],
        ];
    }

    protected function purgeItems(array $scope, Software $em, int $max): int
    {
        global $DB;

        $total = 0;
        do {
            $scope['LIMIT'] = min($max - $total, self::MAX_BATCH_SIZE);
            $items = $DB->request($scope);
            $count = count($items);
            $total += $count;
            foreach ($items as $item) {
                $em->delete($item, true);
            }
        } while ($count > 0 && $total < $max);
        return $total;
    }
}

<?php

namespace Glpi\Console\Migration;

use Glpi\Console\AbstractCommand;
use Glpi\Exception\Database\QueryException;
use Log;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildMissingTimestampsCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('migration:build_missing_timestamps');
        $this->setDescription(__('Set missing `date_creation` and `date_mod` values using log entries.'));
        $this->setHidden(true); // Hide this command as it is when migrating from really old GLPI version
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $tables_iterator = $this->getDb()->request(
            [
                'SELECT' => [
                    'table_name AS TABLE_NAME',
                    'column_name AS COLUMN_NAME',
                ],
                'FROM'   => 'information_schema.columns',
                'WHERE'  => [
                    'table_schema' => $this->getDb()->dbdefault,
                    'table_name'   => ['LIKE', 'glpi\_%'],
                    'column_name'  => ['date_creation', 'date_mod'],
                ],
                'ORDER'  => ['table_name', 'column_name'],
            ]
        );

        $log_table = Log::getTable();

        foreach ($tables_iterator as $table_info) {
            $table    = $table_info['TABLE_NAME'];
            $item     = getItemForTable($table);
            $itemtype = $item::class;
            $column   = $table_info['COLUMN_NAME'];

            if ($item === null) {
                continue;
            }

            if (!$item->dohistory) {
                continue; // Skip items that does not have an history
            }

            $output->writeln(
                '<comment>' . sprintf(__('Filling `%s`.`%s`...'), $table, $column) . '</comment>',
                OutputInterface::VERBOSITY_VERBOSE
            );

            $target_date = $column === 'date_creation' ? 'MIN(`date_mod`)' : 'MAX(`date_mod`)';

            try {
                $result = $this->getDb()->doQuery(
                    "
            UPDATE `$table`
            LEFT JOIN (
               SELECT $target_date AS `date_mod`, `itemtype`, `items_id`
               FROM  `$log_table`
               GROUP BY `itemtype`, `items_id`
            ) as `logs`
            ON `logs`.`itemtype` = '$itemtype' AND `logs`.`items_id` = `$table`.`id`
            SET  `$table`.`$column` = `logs`.`date_mod` WHERE `$table`.`$column` IS NULL
            "
                );
            } catch (QueryException $e) {
                $message = sprintf(
                    __('Update of `%s`.`%s` failed with message "(%s) %s".'),
                    $table,
                    $column,
                    $this->getDb()->errno(),
                    $this->getDb()->error()
                );
                $output->writeln(
                    '<error>' . $message . '</error>',
                    OutputInterface::VERBOSITY_QUIET
                );
            }
        }

        $output->writeln('<info>' . __('Migration done.') . '</info>');

        return 0; // Success
    }
}

<?php

use Glpi\DBAL\QueryExpression;

use function Safe\preg_replace;

foreach (['glpi_computervirtualmachines', 'glpi_networkequipments'] as $table) {
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    // field has to be nullable to be able to set empty values to null
    $migration->changeField(
        $table,
        'ram',
        'ram',
        'varchar(255) DEFAULT NULL',
    );
    $migration->migrationOneTable($table);

    $iterator = $DB->request([
        'FROM'  => $table,
        'WHERE' => [
            'ram' => ['REGEXP', '[^0-9]+'],
        ],
    ]);
    foreach ($iterator as $row) {
        $DB->update(
            $table,
            ['ram' => preg_replace('/[^0-9]+/', '', $row['ram'])],
            ['id'  => $row['id']]
        );
    }
    $DB->update(
        $table,
        ['ram' => null],
        [
            'OR' => [
                'ram' => '',
                // We expect the `ram` value to be expressed in MiB, so if the value exceeds the maximum value of the field,
                // it is probably invalid, since it corresponds to more than 4096 TiB of RAM.
                // Setting value to null will prevent a `Out of range value for column 'ram'` SQL error.
                new QueryExpression(sprintf('CAST(%s AS UNSIGNED) >= POW(2, 32)', 'ram')),
            ],
        ]
    );
    $migration->changeField(
        $table,
        'ram',
        'ram',
        'int unsigned DEFAULT NULL',
    );
}

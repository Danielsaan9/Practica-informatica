<?php

/** @file
 * @brief
 */

/**
 * Update from 9.2.1 to 9.2.2
 *
 * @return bool
 **/
function update921to922()
{
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;

    $migration->setVersion('9.2.2');

    $migration->addConfig([
        'smtp_retry_time' => 5,
    ]);

    $migration->addPostQuery(
        $DB->buildDelete("glpi_configs", [
            'context'   => "core",
            'name'      => "default_graphtype",
        ])
    );

    $migration->addPostQuery(
        $DB->buildDelete(
            "glpi_crontasks",
            ['name' => "optimize"]
        )
    );

    // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}

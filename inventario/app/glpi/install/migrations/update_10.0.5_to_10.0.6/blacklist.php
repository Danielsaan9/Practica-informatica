<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
if (countElementsInTable(Blacklist::getTable(), ["type" => Blacklist::IP, "value" => "::1"]) === 0) {
    $migration->addPostQuery(
        $DB->buildInsert(
            'glpi_blacklists',
            [
                'name'      => 'IPV6 localhost',
                'value' => '::1',
                'type' => Blacklist::IP,
            ]
        )
    );
}

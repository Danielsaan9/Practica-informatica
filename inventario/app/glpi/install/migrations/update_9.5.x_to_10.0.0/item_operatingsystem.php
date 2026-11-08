<?php

/** Replace -1 values for glpi_items_operatingsystems table foreign key fields */
// Migration may have been missed if user installed 10.x version before 9.5.7 release date.
foreach (['operatingsystems_id', 'operatingsystemversions_id', 'operatingsystemservicepacks_id'] as $item_os_fkey) {
    /**
     * @var DBmysql $DB
     * @var Migration $migration
     */
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_items_operatingsystems',
            [$item_os_fkey => '0'],
            [$item_os_fkey => '-1']
        )
    );
}
/** /Replace -1 values for glpi_items_operatingsystems table foreign key fields */

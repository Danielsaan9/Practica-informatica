<?php

/**
 * @var Migration $migration
 */
$migration->addConfig([
    'use_flat_dropdowntree_on_search_result'   => 1,
]);
$migration->addField('glpi_users', 'use_flat_dropdowntree_on_search_result', 'tinyint DEFAULT NULL');

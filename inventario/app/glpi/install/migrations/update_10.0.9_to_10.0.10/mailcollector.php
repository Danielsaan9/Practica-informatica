<?php

/**
 * @var array $ADDTODISPLAYPREF
 * @var Migration $migration
 */
/* Add `last_collect_date` to some glpi_mailcollectors */
$migration->addField('glpi_mailcollectors', 'last_collect_date', 'timestamp');
$migration->addKey('glpi_mailcollectors', 'last_collect_date', 'last_collect_date');
$ADDTODISPLAYPREF['MailCollector'] = [23];

<?php

use Glpi\Event;

/**
 * @var array $ADDTODISPLAYPREF
 * @var Migration $migration
 */
$ADDTODISPLAYPREF['Glpi\Event'] = [155, 156, 157, 158, 159, 160];

$migration->renameItemtype('Event', Event::class);

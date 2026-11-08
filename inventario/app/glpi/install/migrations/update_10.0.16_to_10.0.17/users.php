<?php

/**
 * @var DBmysql $DB
 * @var Migration $migration
 */
$migration->changeField('glpi_users', 'entities_id', 'entities_id', "int unsigned DEFAULT '0'");

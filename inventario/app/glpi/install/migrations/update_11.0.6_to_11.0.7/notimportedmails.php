<?php

/**
 * @var Migration $migration
 */

// !43122 prevent SQL error when messageid exceeds column size
$migration->changeField('glpi_notimportedemails', 'messageid', 'messageid', 'varchar(1000) NOT NULL');

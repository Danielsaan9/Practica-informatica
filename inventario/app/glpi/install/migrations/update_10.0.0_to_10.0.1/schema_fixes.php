<?php

/**
 * @var Migration $migration
 */
// Remove '' default values on glpi_impactcontexts.positions
// MySQL does not allow default values on TEXT fields, while MariaDB does
// Default was removed in installation file GLPI 9.5.4, see #8415
$migration->changeField('glpi_impactcontexts', 'positions', 'positions', 'mediumtext NOT NULL');

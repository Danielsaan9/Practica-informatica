<?php

/**
 * @var Migration $migration
 */
$migration->changeField('glpi_impactitems', 'is_slave', 'is_dependent', 'bool', ['value' => 1]);

<?php

/**
 * @var Migration $migration
 */
$migration->changeField('glpi_tickettemplatepredefinedfields', 'value', 'value', 'longtext', ['nodefault' => true]);
$migration->changeField('glpi_changetemplatepredefinedfields', 'value', 'value', 'longtext', ['nodefault' => true]);
$migration->changeField('glpi_problemtemplatepredefinedfields', 'value', 'value', 'longtext', ['nodefault' => true]);

<?php

/**
 * @var Migration $migration
 */
// Add pendingreasons_id field
$migration->addField("glpi_tasktemplates", "pendingreasons_id", "fkey");
$migration->addKey("glpi_tasktemplates", "pendingreasons_id");

// Add dedicated right
$migration->addRight('tasktemplate', READ, ['taskcategory' => READ]);
$migration->replaceRight('tasktemplate', READ | UPDATE, ['taskcategory' => UPDATE]);
$migration->replaceRight('tasktemplate', READ | UPDATE | CREATE, ['taskcategory' => CREATE]);
$migration->replaceRight('tasktemplate', READ | UPDATE | CREATE | PURGE, ['taskcategory' => PURGE]);

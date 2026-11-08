<?php

/**
 * @var Migration $migration
 */
$migration->addField("glpi_authmails", "is_default", "TINYINT NOT NULL DEFAULT 0");

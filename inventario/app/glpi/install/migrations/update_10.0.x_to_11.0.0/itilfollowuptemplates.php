<?php

/**
 * @var Migration $migration
 */
// Add pendingreasons_id field
$migration->addField("glpi_itilfollowuptemplates", "pendingreasons_id", "fkey");
$migration->addKey("glpi_itilfollowuptemplates", "pendingreasons_id");

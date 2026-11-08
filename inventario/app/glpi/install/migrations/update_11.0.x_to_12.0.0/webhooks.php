<?php

/**
 * @var Migration $migration
 */

// Existing webhooks were built against the v2 payloads. Without this, they would silently
// switch to the next major version as soon as it becomes the router default.
$migration->addField('glpi_webhooks', 'pinned_version', 'string', [
    'update' => "'2'",
]);

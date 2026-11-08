<?php

/**
 * @var Migration $migration
 */
// Drop unexpected values related to SQL Replication
$migration->removeConfig([
    '_dbslave_status',
    '_dbreplicate_dbhost',
    '_dbreplicate_dbuser',
    '_dbreplicate_dbpassword',
    '_dbreplicate_dbdefault',
]);

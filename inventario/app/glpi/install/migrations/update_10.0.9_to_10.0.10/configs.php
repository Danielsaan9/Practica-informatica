<?php

/**
 * @var Migration $migration
 */
// Drop unexpected `['0' => 'system_user']` config added by buggy 9.5.x -> 10.0.0 migration.
$migration->removeConfig(['0']);

<?php

/**
 * @var Migration $migration
 */
$migration->addConfig([
    'planning_work_days' => exportArrayToDB([0, 1, 2, 3, 4, 5, 6]),
]);

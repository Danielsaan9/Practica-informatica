<?php

/**
 * @var Migration $migration
 */
$migration->changeField(
    NetworkPortFiberchannel::getTable(),
    'wwn',
    'wwn',
    "varchar(50) DEFAULT ''",
);

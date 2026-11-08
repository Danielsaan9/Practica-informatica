<?php

/**
 * @var Migration $migration
 */
use function Safe\ini_get;

// Add a config entry for document max size.
// Default size corresponds to the 'upload_max_filesize' directive in Mio (rounded down)
// or 1 Mio if 'upload_max_filesize' is too low.
$upload_max = Toolbox::return_bytes_from_ini_vars(ini_get('upload_max_filesize'));
$migration->addConfig(['document_max_size' => max(1, floor($upload_max / 1024 / 1024))]);

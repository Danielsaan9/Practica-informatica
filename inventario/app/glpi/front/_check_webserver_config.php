<?php

use Glpi\Kernel\Kernel;

if (!class_exists(Kernel::class, autoload: false)) {
    // `Glpi\Kernel\Kernel` class will exists if the request was processed by the `/public/index.php` file,
    // and will not be found otherwise.
    header('HTTP/1.1 404 Not Found');
    readfile(__DIR__ . '/../index.html'); // @phpstan-ignore theCodingMachineSafe.function (vendor libs are not yet loaded)
    exit(); // @phpstan-ignore glpi.forbidExit (Script execution should be stopped to prevent further errors)
}

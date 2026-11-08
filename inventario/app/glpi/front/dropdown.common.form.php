<?php

require_once(__DIR__ . '/_check_webserver_config.php');

/**
 * @var mixed $this
 * @var mixed $dropdown
 * @var mixed $options
 */

use Glpi\Controller\DropdownFormController;
use Glpi\Controller\LegacyFileLoadController;

if (!($this instanceof LegacyFileLoadController) || !($dropdown instanceof CommonDropdown)) {
    throw new LogicException();
}

Toolbox::deprecated(\sprintf(
    'Requiring legacy dropdown files is deprecated. You can safely remove the `%s` file in order to make the `%s` controller used instead.',
    debug_backtrace()[0]['file'] ?? 'including',
    DropdownFormController::class,
));

$request = $this->getRequest(); // @phpstan-ignore method.private
$request->attributes->set('class', $dropdown::class);

if (($options ?? null) !== null) {
    $request->attributes->set('options', $options);
}

$controller = new DropdownFormController();
$response = $controller($request);
$response->send();

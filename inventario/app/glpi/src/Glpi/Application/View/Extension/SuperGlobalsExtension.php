<?php

namespace Glpi\Application\View\Extension;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * Exposes PHP superglobals as Twig globals (_post, _get, _request).
 *
 * @since 12.0.0
 */
class SuperGlobalsExtension extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals(): array
    {
        return [
            '_post'    => $_POST,
            '_get'     => $_GET,
            '_request' => $_REQUEST,
        ];
    }
}

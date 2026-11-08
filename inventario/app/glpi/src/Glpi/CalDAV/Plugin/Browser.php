<?php

namespace Glpi\CalDAV\Plugin;

use Config;
use Glpi\Application\Environment;
use Glpi\CalDAV\Traits\CalDAVUriUtilTrait;
use Sabre\DAV\Browser\Plugin;
use Sabre\HTTP\RequestInterface;
use Sabre\HTTP\ResponseInterface;

/**
 * Browser plugin for CalDAV server.
 *
 * @since 9.5.0
 */
class Browser extends Plugin
{
    use CalDAVUriUtilTrait;

    public function httpGet(RequestInterface $request, ResponseInterface $response)
    {
        if (!$this->canDisplayDebugInterface()) {
            return false;
        }

        return parent::httpGet($request, $response);
    }

    /**
     * Check if connected user can display the HTML frontend.
     *
     * @return bool
     */
    private function canDisplayDebugInterface()
    {
        return Environment::get()->shouldEnableExtraDevAndDebugTools() || Config::canUpdate();
    }
}

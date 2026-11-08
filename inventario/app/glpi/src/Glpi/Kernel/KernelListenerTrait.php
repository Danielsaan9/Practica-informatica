<?php

namespace Glpi\Kernel;

use Config;
use DBConnection;
use Symfony\Component\HttpFoundation\Request;
use Update;

trait KernelListenerTrait
{
    /**
     * Indicates whether a controller has already been assigned to the request.
     */
    protected function isControllerAlreadyAssigned(Request $request): bool
    {
        return $request->attributes->get('_controller') !== null;
    }

    /**
     * Indicates whether the requested resource is made on a front-end asset endpoint.
     */
    protected function isFrontEndAssetEndpoint(Request $request): bool
    {
        $path = $request->getPathInfo();

        return \str_starts_with($path, '/js/')
            || \str_starts_with($path, '/front/css.php')
            || \str_starts_with($path, '/front/locale.php');
    }

    /**
     * Indicates whether the requested resource is made on the Symfony profiler resources.
     */
    protected function isSymfonyProfilerEndpoint(Request $request): bool
    {
        $path = $request->getPathInfo();

        return \str_starts_with($path, '/_profiler/')
            || \str_starts_with($path, '/_wdt/');
    }

    /**
     * Indicates whether the database data can be used.
     * For instance, plugins and custom objects definitions should not be loaded if a mandatory update is required.
     */
    protected function isDatabaseUsable(): bool
    {
        return DBConnection::isDbAvailable()
            && Config::isLegacyConfigurationLoaded()
            && Update::isUpdateMandatory() === false;
    }
}

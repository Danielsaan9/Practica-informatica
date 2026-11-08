<?php

declare(strict_types=1);

namespace Glpi\Security\ReAuth;

abstract class InPlaceReAuthStrategy implements ReAuthStrategyInterface
{
    /**
     * Verify url points to \Glpi\Controller\Security\ReAuthController::verify
     *
     * Providing another value allows prompt form validation to bypass the ReauthStrategy verify().
     * A use case is when verification is done by an external authentication service.
     * Before overriding it, ensure you really need it because it will bypass ReAuthStrategyInterface::verify().
     *
     * @see ReAuthStrategyInterface::getVerifyUrl()
     * @see ReAuthStrategyInterface::verify()
     */
    public function getVerifyUrl(): string
    {
        global $CFG_GLPI;

        return $CFG_GLPI['root_doc'] . '/ReAuth/Verify';
    }

    /**
     * @see ReAuthStrategyInterface::getVerifyHttpMethod()
     */
    public function getVerifyHttpMethod(): string
    {
        return 'POST';
    }
}

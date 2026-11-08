<?php

namespace Glpi\Controller\Security;

use Config;
use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Http\Firewall;
use Glpi\Http\RedirectResponse;
use Glpi\Security\Attribute\SecurityStrategy;
use Glpi\Security\SecurityConfig;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityConfigController extends AbstractController
{
    #[Route(
        path: "/front/security/securityconfig.form.php",
        name: "security_config_form",
        methods: ['GET']
    )]
    #[SecurityStrategy(Firewall::STRATEGY_CENTRAL_ACCESS)]
    public function showForm(Request $request): Response
    {
        (new SecurityConfig())->checkGlobal(READ);

        $config_id = Config::getConfigIDForContext('core');
        if ($config_id === null) {
            throw new RuntimeException('Unable to find any configs for context "core"');
        }

        SecurityConfig::displayFullPageForItem($config_id, ["config", SecurityConfig::class], [
            'formoptions'  => "data-track-changes=true",
        ]);
        return new Response();
    }

    #[Route(
        path: "/front/security/securityconfig.form.php",
        name: "update_security_config_form",
        methods: ['POST']
    )]
    #[SecurityStrategy(Firewall::STRATEGY_ADMIN_ACCESS)]
    public function handleFormSubmission(Request $request): Response
    {
        $security_config = new SecurityConfig();

        // despite #[SecurityStrategy(Firewall::STRATEGY_ADMIN_ACCESS)],
        // we need to check UPDATE right on config because of the reauth mechanism redirection
        // otherwise reauth is bypassed
        $security_config->checkGlobal(UPDATE);

        $do_update = $request->request->getBoolean('update');
        if (!$do_update) {
            throw new BadRequestHttpException('"update" parameter must be set');
        }

        $config_id = Config::getConfigIDForContext('core');
        if ($config_id === null) {
            throw new RuntimeException('Unable to find any configs for context "core"');
        }

        $update_input = $request->request->all();
        $update_input['id'] = $config_id;
        $security_config->update($update_input);
        return new RedirectResponse(SecurityConfig::getFormURLWithID($config_id));
    }
}

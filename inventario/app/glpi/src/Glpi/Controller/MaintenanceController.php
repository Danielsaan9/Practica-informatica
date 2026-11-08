<?php

declare(strict_types=1);

namespace Glpi\Controller;

use Glpi\Http\Firewall;
use Glpi\Locale\LanguageRegistry;
use Glpi\Security\Attribute\SecurityStrategy;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceController extends AbstractController
{
    /**
     * Internal route that displays the "maintenance" page.
     */
    #[SecurityStrategy(Firewall::STRATEGY_NO_CHECK)]
    public function __invoke(): Response
    {
        return $this->render(
            'maintenance.html.twig',
            [
                'lang'      => LanguageRegistry::get($_SESSION['glpilanguage'])->getPageLang(),
            ]
        );
    }
}

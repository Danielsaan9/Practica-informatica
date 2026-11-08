<?php

namespace Glpi\Controller;

use Central;
use Glpi\Dashboard\Grid;
use Glpi\Http\Firewall;
use Glpi\Http\RedirectResponse;
use Glpi\Security\Attribute\SecurityStrategy;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Toolbox;

class CentralController extends AbstractController
{
    #[Route('/front/central.php', name: 'front_central_legacy')]
    #[Route('/Central', name: 'front_central')]
    #[SecurityStrategy(Firewall::STRATEGY_NO_CHECK)] // No automatic check, to allow embed dashboards
    public function __invoke(Request $request): Response
    {
        if ($request->query->has('embed') && $request->query->has('dashboard')) {
            // embed (anonymous) dashboard
            $grid = new Grid($request->query->get('dashboard'));
            $grid->initEmbed($request->query->all());

            return $this->render('central/embed_dashboard.html.twig', [
                'grid'  => $grid,
                'token' => $request->query->get('token'),
            ]);
        }

        Session::checkCentralAccess();

        if (
            $request->query->has('redirect')
            && $url = Toolbox::computeRedirect($request->query->get('redirect'))
        ) {
            return new RedirectResponse($url);
        }

        return $this->render('pages/central/index.html.twig', [
            'central' => new Central(),
        ]);
    }
}

<?php

namespace Glpi\Controller\ItemType\Form;

use Glpi\Controller\GenericFormController;
use Glpi\Http\Firewall;
use Glpi\Http\RedirectResponse;
use Glpi\Routing\Attribute\ItemtypeFormRoute;
use Glpi\Security\Attribute\SecurityStrategy;
use Html;
use SavedSearch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SavedSearchFormController extends GenericFormController
{
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    #[ItemtypeFormRoute(SavedSearch::class)]
    public function __invoke(Request $request): Response
    {
        $request->attributes->set('class', SavedSearch::class);

        if ($request->query->has('create_notif')) {
            return $this->createNotif();
        }

        return parent::__invoke($request);
    }

    public function createNotif(): RedirectResponse
    {
        $savedsearch = new SavedSearch();
        $savedsearch->check($_GET['id'], UPDATE);
        $savedsearch->createNotif();

        return new RedirectResponse(Html::getBackUrl());
    }
}

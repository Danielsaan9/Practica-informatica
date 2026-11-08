<?php

namespace Glpi\Controller\ItemType\Form;

use AuthMail;
use Glpi\Controller\GenericFormController;
use Glpi\Http\RedirectResponse;
use Glpi\Routing\Attribute\ItemtypeFormRoute;
use Html;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMailFormController extends GenericFormController
{
    #[ItemtypeFormRoute(AuthMail::class)]
    public function __invoke(Request $request): Response
    {
        $request->attributes->set('class', AuthMail::class);

        if ($request->request->has('test')) {
            return $this->handleTestAction($request);
        }

        return parent::__invoke($request);
    }

    public function handleTestAction(Request $request): RedirectResponse
    {
        $test_auth = AuthMail::testAuth(
            $request->request->get("imap_string"),
            $request->request->get("imap_login"),
            $request->request->get("imap_password"),
        );

        if ($test_auth) {
            Session::addMessageAfterRedirect(__s('Test successful'));
        } else {
            Session::addMessageAfterRedirect(__s('Test failed'), false, ERROR);
        }

        return new RedirectResponse(Html::getBackUrl());
    }
}

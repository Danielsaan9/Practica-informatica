<?php

namespace Glpi\Controller\ItemType\Form;

use Glpi\Controller\GenericFormController;
use Glpi\Http\RedirectResponse;
use Glpi\Routing\Attribute\ItemtypeFormRoute;
use MailCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailCollectorFormController extends GenericFormController
{
    #[ItemtypeFormRoute(MailCollector::class)]
    public function __invoke(Request $request): Response
    {
        $request->attributes->set('class', MailCollector::class);

        if (
            $request->request->has('get_mails')
        ) {
            $object = new MailCollector();
            $object->check($request->request->get('id'), UPDATE);
            $object->collect($request->request->get('id'), true);

            return new RedirectResponse($object->getLinkURL());
        }

        return parent::__invoke($request);
    }
}

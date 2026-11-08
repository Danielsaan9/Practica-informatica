<?php

namespace Glpi\Controller\ItemType\Form;

use Contact;
use Glpi\Controller\GenericFormController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Routing\Attribute\ItemtypeFormRoute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactFormController extends GenericFormController
{
    #[ItemtypeFormRoute(Contact::class)]
    public function __invoke(Request $request): Response
    {
        $request->attributes->set('class', Contact::class);

        if ($request->query->has('getvcard')) {
            return $this->generateVCard($request);
        }

        return parent::__invoke($request);
    }

    private function generateVCard(Request $request): Response
    {
        $id = $request->query->getInt('id');

        if (Contact::isNewID($id)) {
            throw new BadRequestHttpException();
        }

        $contact = new Contact();
        if (!$contact->can($id, READ)) {
            throw new AccessDeniedHttpException();
        }

        return new StreamedResponse(fn() => $contact->generateVcard());
    }
}

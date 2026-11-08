<?php

namespace Glpi\Controller\Form\Destination;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Form\Destination\FormDestination;
use Glpi\Form\Form;
use Glpi\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddDestinationController extends AbstractController
{
    #[Route("/Form/{form_id}/Destination/Add", name: "glpi_form_destination_add", methods: "POST")]
    public function __invoke(Request $request, int $form_id): Response
    {
        $destination = new FormDestination();
        $input = array_merge($request->request->all(), [Form::getForeignKeyField() => $form_id]);

        // Right check
        if (!$destination->can(-1, CREATE, $input)) {
            throw new AccessDeniedHttpException();
        }

        // If we are duplicating an item, load the full data.
        if (isset($input['id'])) {
            $original = FormDestination::getById($input['id']);
            $input = $original->fields;
            unset($input['id']); // Id must be removed to prevent a clone from triggering.
        }

        $input['_do_not_clone'] = true;

        // Create destination item
        $id = $destination->add($input);
        if (!$id) {
            throw new BadRequestHttpException();
        }

        // Save the ID to reopen the correct accordion item
        $_SESSION['active_destination'] = $id;

        return new RedirectResponse(Form::getFormURLWithID($form_id));
    }
}

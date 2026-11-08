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

final class UpdateDestinationController extends AbstractController
{
    #[Route("/Form/{form_id}/Destination/{destination_id}/Update", name: "glpi_form_destination_update", methods: "POST")]
    public function __invoke(Request $request, int $form_id, int $destination_id): Response
    {
        $destination = new FormDestination();
        $input = array_merge(
            $request->request->all(),
            [
                'id'                       => $destination_id,
                Form::getForeignKeyField() => $form_id,
            ]
        );

        // Right check
        if (!$destination->can($destination_id, UPDATE, $input)) {
            throw new AccessDeniedHttpException();
        }

        // Update destination item
        if (!$destination->update($input)) {
            throw new BadRequestHttpException('Failed to update destination item');
        }

        // Save the ID to reopen the correct accordion item
        $_SESSION['active_destination'] = $destination_id;

        return new RedirectResponse(Form::getFormURLWithID($form_id));
    }
}

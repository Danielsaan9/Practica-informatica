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

final class PurgeDestinationController extends AbstractController
{
    #[Route("/Form/{form_id}/Destination/{destination_id}/Purge", name: "glpi_form_destination_purge", methods: "POST")]
    public function __invoke(Request $request, int $form_id, int $destination_id): Response
    {
        $destination = new FormDestination();
        $input = array_merge($request->request->all(), ['id' => $destination_id]);

        // Right check
        if (!$destination->can($destination_id, DELETE, $input)) {
            throw new AccessDeniedHttpException();
        }

        // Delete destination item
        if (!$destination->delete($input)) {
            throw new BadRequestHttpException('Failed to delete destination item');
        }

        return new RedirectResponse(Form::getFormURLWithID($form_id));
    }
}

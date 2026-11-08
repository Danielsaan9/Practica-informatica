<?php

namespace Glpi\Controller\Form;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Form\Export\Serializer\FormSerializer;
use Glpi\Form\Form;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ExportController extends AbstractController
{
    #[Route("/Form/Export", name: "glpi_form_export")]
    public function __invoke(Request $request): Response
    {
        // Right check
        if (!Form::canView()) {
            throw new AccessDeniedHttpException();
        }

        // Read parameters
        $ids = $request->query->all()["ids"] ?? [];

        // Ensure user can view all forms
        $forms = array_filter(Form::getByIds($ids), fn(Form $form) => $form->can($form->getID(), READ));

        // Execute export
        $serializer = new FormSerializer();
        $export = $serializer->exportFormsToJson($forms);

        // Output file
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $export->getFileName(),
        );
        $response = new Response($export->getJsonContent());
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}

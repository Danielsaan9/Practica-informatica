<?php

namespace Glpi\Controller\Form\Import;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Initial entry point of the import form process.
 */
final class Step1IndexController extends AbstractController
{
    #[Route("/Form/Import", name: "glpi_form_import", methods: "GET")]
    public function __invoke(Request $request): Response
    {
        if (!Form::canCreate()) {
            throw new AccessDeniedHttpException();
        }

        return $this->render("pages/admin/form/import/step1_index.html.twig", [
            'title' => __("Import form"),
            'menu'  => ['admin', Form::class],
        ]);
    }
}

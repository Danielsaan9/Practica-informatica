<?php

namespace Glpi\Controller\Form\Import;

use Glpi\Controller\AbstractController;
use Glpi\Form\Export\Context\DatabaseMapper;
use Glpi\Form\Export\Serializer\FormSerializer;
use Glpi\Form\Form;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class Step3ResolveIssuesController extends AbstractController
{
    #[Route("/Form/Import/ResolveIssues", name: "glpi_form_import_resolve_issues", methods: "POST")]
    public function __invoke(Request $request): Response
    {
        if (!Form::canCreate()) {
            throw new AccessDeniedHttpException();
        }

        // Get json, form name and skipped_forms from the request.
        $json          = $request->request->get('json');
        $form_id       = $request->request->get('form_id');
        $skipped_forms = $request->request->all()["skipped_forms"] ?? [];

        $serializer = new FormSerializer();
        $mapper = new DatabaseMapper(Session::getActiveEntities());

        $replacements = $request->request->all()["replacements"] ?? [];
        foreach ($replacements as $replacements_data) {
            $mapper->addMappedItem(
                $replacements_data['itemtype'],
                $replacements_data['original_name'],
                (int) $replacements_data['replacement_id'],
            );
        }

        $issues = $serializer->listIssues($mapper, $json)->getIssues()[$form_id];
        return $this->render("pages/admin/form/import/step3_resolve_issues.html.twig", [
            'title'        => __("Resolve issues"),
            'menu'         => ['admin', Form::class],
            'issues'       => $issues,
            'json'         => $json,
            'replacements' => $replacements,
            'skipped_forms' => $skipped_forms,
        ]);
    }
}

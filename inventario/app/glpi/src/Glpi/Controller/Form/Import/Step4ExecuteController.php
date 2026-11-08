<?php

namespace Glpi\Controller\Form\Import;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Form\Export\Context\DatabaseMapper;
use Glpi\Form\Export\Serializer\FormSerializer;
use Glpi\Form\Form;
use Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Step4ExecuteController extends AbstractController
{
    #[Route("/Form/Import/Execute", name: "glpi_form_import_execute", methods: "POST")]
    public function __invoke(Request $request): Response
    {
        if (!Form::canCreate()) {
            throw new AccessDeniedHttpException();
        }

        // Get json and skipped forms from hidden inputs
        $json = $request->request->get('json');
        $skipped_forms = $request->request->all()["skipped_forms"] ?? [];

        $serializer = new FormSerializer();
        $mapper = new DatabaseMapper(Session::getActiveEntities());

        $replacements = $request->request->all()["replacements"] ?? [];
        foreach ($replacements as $replacement_data) {
            $mapper->addMappedItem(
                $replacement_data['itemtype'],
                $replacement_data['original_name'],
                $replacement_data['replacement_id']
            );
        }

        return $this->render("pages/admin/form/import/step4_execute.html.twig", [
            'title'   => __("Import results"),
            'menu'    => ['admin', Form::class],
            'results' => $serializer->importFormsFromJson($json, $mapper, $skipped_forms),
        ]);
    }
}

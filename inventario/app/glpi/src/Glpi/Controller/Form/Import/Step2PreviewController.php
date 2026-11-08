<?php

namespace Glpi\Controller\Form\Import;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Form\Export\Context\DatabaseMapper;
use Glpi\Form\Export\Serializer\FormSerializer;
use Glpi\Form\Form;
use Glpi\Http\RedirectResponse;
use Session;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Step2PreviewController extends AbstractController
{
    #[Route("/Form/Import/Preview", name: "glpi_form_import_preview", methods: "POST")]
    public function __invoke(Request $request): Response
    {
        if (!Form::canCreate()) {
            throw new AccessDeniedHttpException();
        }

        $json = $this->getJsonFormFromRequest($request);
        $skipped_forms = $request->request->all()["skipped_forms"] ?? [];
        $replacements = $request->request->all()["replacements"] ?? [];

        return $this->previewResponse($request, $json, $skipped_forms, $replacements);
    }

    private function previewResponse(
        Request $request,
        string $json,
        array $skipped_forms,
        array $replacements
    ): Response {
        $serializer = new FormSerializer();
        $mapper = new DatabaseMapper(Session::getActiveEntities());
        foreach ($replacements as $replacement_data) {
            $mapper->addMappedItem(
                $replacement_data['itemtype'],
                $replacement_data['original_name'],
                $replacement_data['replacement_id']
            );
        }

        $previewResult = $serializer->previewImport($json, $mapper, $skipped_forms);
        if (
            empty($previewResult->getValidForms())
            && empty($previewResult->getInvalidForms())
            && empty($previewResult->getFormsWithFatalErrors())
        ) {
            return new RedirectResponse($request->getBasePath() . '/Form/Import');
        }

        return $this->render("pages/admin/form/import/step2_preview.html.twig", [
            'title'        => __("Preview import"),
            'menu'         => ['admin', Form::class],
            'preview'      => $previewResult,
            'json'         => $json,
            'replacements' => $replacements,
        ]);
    }

    private function getJsonFormFromRequest(Request $request): string
    {
        if ($request->request->has('json')) {
            return $request->request->get('json');
        }

        /** @var UploadedFile $file */
        $file = $request->files->get('import_file');

        return $file->getContent();
    }
}

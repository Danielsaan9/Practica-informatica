<?php

namespace Glpi\Controller\Form\Translation;

use Glpi\Controller\Translation\AbstractTranslationController;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\Form\Form;
use Glpi\Form\FormTranslation;
use Glpi\ItemTranslation\CldrLanguage;
use Glpi\ItemTranslation\ItemTranslation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateFormTranslationController extends AbstractTranslationController
{
    private Form $form;

    #[Route("/Form/Translation/{form_id}/{language}", name: "glpi_update_form_translation", methods: "POST")]
    public function __invoke(Request $request, int $form_id, string $language): Response
    {
        // Retrieve the form from the database
        $this->form = new Form();
        if (!$this->form->getFromDB($form_id)) {
            throw new NotFoundHttpException('Form not found');
        }

        // Validate the language code
        $this->validateLanguage($language);

        $cldr_language = new CldrLanguage($language);
        $category_index = $cldr_language->getPluralKey(1);

        $input = $request->toArray();
        $input['translations'] = $this->remapFileUploadsToTranslations($category_index, $input);

        if ($this->processTranslations($input['translations'], $language)) {
            $this->addSuccessMessage($language);
        }

        return new JsonResponse([
            'redirect' => $this->getRedirectUrl(),
        ]);
    }

    protected function getTranslationClass(): ItemTranslation
    {
        return new FormTranslation();
    }

    protected function getRedirectUrl(?string $language = null): string
    {
        return $this->form->getFormURLWithID($this->form->getID());
    }

    protected function getTranslationHandlers(): array
    {
        return $this->form->listTranslationsHandlers();
    }

    protected function getContextTranslations(?string $language = null): array
    {
        return FormTranslation::getTranslationsForItem($this->form);
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function remapFileUploadsToTranslations(string $category_index, array $input): array
    {
        $translations = $input['translations'] ?? [];
        $file_upload_prefixes = ['_', '_prefix_', '_tag_'];

        foreach ($file_upload_prefixes as $prefix) {
            $prefixed_key = $prefix . 'translations';
            if (!isset($input[$prefixed_key])) {
                continue;
            }

            foreach ($input[$prefixed_key] as $translation_key => $data) {
                if (!is_array($data) || !isset($data['translations'][$category_index])) {
                    continue;
                }

                $translations[$translation_key]['translations'][$prefix . $category_index] = $data['translations'][$category_index];
            }
        }

        return $translations;
    }
}

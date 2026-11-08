<?php

namespace Glpi\Controller\Form\Translation;

use Dropdown;
use Glpi\Application\View\TemplateRenderer;
use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\Form\Form;
use Glpi\Form\FormTranslation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GetFormTranslationController extends AbstractController
{
    #[Route("/Form/Translation/{form_id}/{language}", name: "glpi_get_form_translation", methods: "GET")]
    public function __invoke(Request $request, int $form_id, string $language): Response
    {
        // Retrieve the form from the database
        $form = new Form();
        if (!$form->getFromDB($form_id)) {
            throw new NotFoundHttpException('Form not found');
        }

        // Right check
        if (!$form->can($form_id, READ)) {
            throw new AccessDeniedHttpException();
        }

        // Validate the language code
        if (!\array_key_exists($language, Dropdown::getLanguages())) {
            throw new BadRequestHttpException('Invalid language code');
        }

        $form_translation = current(array_filter(
            FormTranslation::getTranslationsForItem($form),
            fn($translation) => $translation instanceof FormTranslation && $translation->fields['language'] === $language
        ));
        if ($form_translation === false) {
            throw new NotFoundHttpException('Form translation not found');
        }

        return new Response(TemplateRenderer::getInstance()->render(
            'pages/admin/form/form_translation_content.html.twig',
            [
                'form'             => $form,
                'form_translation' => $form_translation,
            ]
        ));
    }
}

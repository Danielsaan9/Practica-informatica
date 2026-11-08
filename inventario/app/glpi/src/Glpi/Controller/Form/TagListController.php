<?php

namespace Glpi\Controller\Form;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\Form\Form;
use Glpi\Form\Tag\FormTagsManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TagListController extends AbstractController
{
    #[Route(
        "/Form/TagList",
        name: "glpi_form_tags_list",
        methods: "GET"
    )]
    public function __invoke(Request $request): Response
    {
        if (!Form::canUpdate()) {
            throw new AccessDeniedHttpException();
        }

        $form = $this->loadSubmittedForm($request);
        $filter = $request->query->getString('filter');

        $tag_manager = new FormTagsManager();
        return new JsonResponse($tag_manager->getTags($form, $filter));
    }

    private function loadSubmittedForm(Request $request): Form
    {
        $forms_id = $request->query->getInt("form_id");
        if (!$forms_id) {
            throw new BadRequestHttpException();
        }

        $form = Form::getById($forms_id);
        if (!$form instanceof Form) {
            throw new NotFoundHttpException();
        }

        return $form;
    }
}

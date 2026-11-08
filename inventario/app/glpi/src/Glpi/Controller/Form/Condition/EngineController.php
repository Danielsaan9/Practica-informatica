<?php

namespace Glpi\Controller\Form\Condition;

use Glpi\Controller\AbstractController;
use Glpi\Controller\Form\Utils\CanCheckAccessPolicies;
use Glpi\Exception\Http\NotFoundHttpException;
use Glpi\Form\Condition\Engine;
use Glpi\Form\Condition\EngineInput;
use Glpi\Form\Form;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EngineController extends AbstractController
{
    use CanCheckAccessPolicies;

    #[Route(
        "/Form/Condition/Engine",
        name: "glpi_form_condition_engine",
        methods: "POST"
    )]
    #[SecurityStrategy(Firewall::STRATEGY_NO_CHECK)]
    public function __invoke(Request $request): Response
    {
        // Load target form
        $form_id = $request->request->getInt('form_id');
        $form = Form::getById($form_id);
        if (!$form) {
            throw new NotFoundHttpException();
        }

        $this->checkFormAccessPolicies($form, $request);

        // Load engine input
        $input = new EngineInput(
            answers: $request->request->all()['answers'] ?? [],
        );

        // Compute visibility
        $engine = new Engine($form, $input);
        return new JsonResponse($engine->computeVisibility());
    }
}

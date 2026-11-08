<?php

namespace Glpi\Controller\UI\Illustration;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Glpi\UI\IllustrationManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CustomSceneController extends AbstractController
{
    public function __construct(
        private IllustrationManager $illustration_manager
    ) {}

    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    #[Route(
        "/UI/Illustration/CustomScene/{id}",
        name: "glpi_ui_illustration_custom_scene",
        methods: "GET",
    )]
    public function __invoke(string $id): Response
    {
        $file = $this->illustration_manager->getCustomSceneFile($id);
        if (!$file) {
            throw new BadRequestHttpException();
        }

        return new BinaryFileResponse($file);
    }
}

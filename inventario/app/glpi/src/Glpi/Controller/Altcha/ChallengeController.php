<?php

namespace Glpi\Controller\Altcha;

use Glpi\Altcha\AltchaManager;
use Glpi\Controller\AbstractController;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChallengeController extends AbstractController
{
    #[SecurityStrategy(Firewall::STRATEGY_NO_CHECK)]
    #[Route(
        "/Altcha/Challenge",
        name: "glpi_altcha_challenge",
        methods: "GET",
    )]
    public function __invoke(): Response
    {
        $challenge = AltchaManager::getInstance()->generateChallenge();

        // The object has its own toJson() implementation, it is better to not
        // use JsonResponse and instead let it handle its own convertion.
        return new Response(
            $challenge->toJson(),
            Response::HTTP_OK,
            ['Content-Type' => 'application/json']
        );
    }
}

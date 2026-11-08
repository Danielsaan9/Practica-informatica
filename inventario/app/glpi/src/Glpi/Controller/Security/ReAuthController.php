<?php

declare(strict_types=1);

namespace Glpi\Controller\Security;

use Glpi\Controller\AbstractController;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Glpi\Security\ReAuth\ReAuthManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ReAuthController extends AbstractController
{
    public function __construct(
        private readonly ReAuthManager $reAuthManager,
    ) {}

    /**
     * @param bool $failed Set by out of band strategies coming back from a failed verification
     */
    #[Route(
        path: "/ReAuth/Prompt",
        name: "reauth_prompt",
        methods: ['GET']
    )]
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function prompt(#[MapQueryParameter] bool $failed = false): Response
    {
        return $this->render(
            'pages/reauth/prompt.html.twig',
            [
                ...$this->buildTemplateContext(),
                'failed' => $failed,
            ]
        );
    }

    #[Route(
        path: "/ReAuth/Verify",
        name: "reauth_verify",
        methods: ['POST']
    )]
    #[SecurityStrategy(Firewall::STRATEGY_AUTHENTICATED)]
    public function verify(Request $request): Response
    {
        if ($this->reAuthManager->verify($request)) {
            $this->reAuthManager->authenticate();

            return $this->render('pages/redirect_post.html.twig', [
                'http_method' => $this->reAuthManager->getRequestedMethod(),
                'url'         => $this->reAuthManager->getRequestedURL(),
                'replay_data' => $this->reAuthManager->getReplayData(),
            ]);
        }

        return $this->prompt(true);
    }

    /**
     * @return array{cancel_url: string, label: string, template: string, verify_url: string, verify_http_method: string}
     */
    private function buildTemplateContext(): array
    {
        return [
            'cancel_url'         => $this->reAuthManager->getOriginURL(),
            'label'              => $this->reAuthManager->getLabel(),
            'template'           => $this->reAuthManager->getPromptTemplate(),
            'verify_url'         => $this->reAuthManager->getVerifyUrl(),
            'verify_http_method' => $this->reAuthManager->getVerifyHttpMethod(),
        ];
    }
}

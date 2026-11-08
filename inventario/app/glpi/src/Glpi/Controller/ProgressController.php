<?php

namespace Glpi\Controller;

use Glpi\Http\Firewall;
use Glpi\Progress\ProgressStorage;
use Glpi\Security\Attribute\SecurityStrategy;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProgressController extends AbstractController
{
    public function __construct(
        private readonly ProgressStorage $progress_storage,
    ) {}

    #[Route("/progress/check/{key}", methods: 'GET')]
    #[SecurityStrategy(Firewall::STRATEGY_NO_CHECK)]
    public function check(string $key): Response
    {
        $progress = $this->progress_storage->getProgressIndicator($key);

        if ($progress === null) {
            return new JsonResponse([], 404);
        }

        return new JsonResponse([
            'started_at'            => $progress->getStartedAt()->format('c'),
            'updated_at'            => $progress->getUpdatedAt()->format('c'),
            'ended_at'              => $progress->getEndedAt()?->format('c'),
            'failed'                => $progress->hasFailed(),
            'current_step'          => $progress->getCurrentStep(),
            'max_steps'             => $progress->getMaxSteps(),
            'progress_bar_message'  => $progress->getProgressBarMessage(),
            'messages'              => $progress->getMessages(),
        ]);
    }
}

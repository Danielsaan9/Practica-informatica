<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Knowbase\SidePanel\CommentsRenderer;
use Glpi\Knowbase\SidePanel\HistoryRenderer;
use Glpi\Knowbase\SidePanel\PermissionsRenderer;
use Glpi\Knowbase\SidePanel\RendererInterface;
use Glpi\Knowbase\SidePanel\ScheduleVisibilityRenderer;
use Glpi\Knowbase\SidePanel\ServiceCatalogRenderer;
use Glpi\Knowbase\SidePanel\SharingRenderer;
use KnowbaseItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SidePanelController extends AbstractController
{
    #[Route(
        "/Knowbase/{id}/SidePanel/{key}",
        name: "knowbase_side_panel",
        requirements: [
            'id' => '\d+',
        ]
    )]
    public function __invoke(int $id, string $key): Response
    {
        // Parse id
        $kb = KnowbaseItem::getById($id);
        if (!$kb) {
            throw new BadRequestHttpException();
        }
        if (!$kb->can($id, READ)) {
            throw new AccessDeniedHttpException();
        }

        // Get renderer
        $renderer = $this->getRendererForKey($key);
        if (!$renderer->canView($kb)) {
            throw new AccessDeniedHttpException();
        }

        // Render content
        return $this->render(
            $renderer->getTemplate(),
            $renderer->getParams($kb),
        );
    }

    private function getRendererForKey(string $key): RendererInterface
    {
        return match ($key) {
            'comments'            => new CommentsRenderer(),
            'service-catalog'     => new ServiceCatalogRenderer(),
            'permissions'         => new PermissionsRenderer(),
            'sharing'             => new SharingRenderer(),
            'history'             => new HistoryRenderer(),
            'schedule-visibility' => new ScheduleVisibilityRenderer(),
            default               => throw new BadRequestHttpException(),
        };
    }
}

<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Knowbase\SidePanel\HistoryRenderer;
use KnowbaseItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Render one page of the article history, to be appended to the history panel
 * as the user scrolls through it.
 *
 * The first page is rendered by the panel itself, see
 * `Glpi\Knowbase\SidePanel\HistoryRenderer`.
 */
final class HistoryPageController extends AbstractController
{
    #[Route(
        "/Knowbase/{id}/HistoryPage/{offset}",
        name: "knowbase_article_history_page",
        methods: ["GET"],
        requirements: [
            'id' => '\d+',
            'offset' => '\d+',
        ]
    )]
    public function __invoke(int $id, int $offset): Response
    {
        // Pages are served one after the other, thus the only valid offsets are
        // the ones this controller handed out through the "load more" marker.
        // Anything else would return events overlapping the previous page.
        if ($offset % HistoryRenderer::PAGE_SIZE !== 0) {
            throw new BadRequestHttpException();
        }

        $kb = KnowbaseItem::getById($id);
        if (!$kb) {
            throw new BadRequestHttpException();
        }
        if (!$kb->can($id, READ)) {
            throw new AccessDeniedHttpException();
        }

        $renderer = new HistoryRenderer();
        if (!$renderer->canView($kb)) {
            throw new AccessDeniedHttpException();
        }

        return $this->render(
            $renderer->getPageTemplate(),
            $renderer->getPageParams($kb, $offset),
        );
    }
}

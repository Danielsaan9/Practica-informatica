<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Http\Firewall;
use Glpi\Knowbase\Aside\SearchResultsBuilder;
use Glpi\Security\Attribute\SecurityStrategy;
use KnowbaseItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Render one page of the aside search results, which replace the article tree
 * for as long as the reader is searching.
 */
final class AsideSearchController extends AbstractController
{
    #[Route(
        "/Knowbase/Aside/Search",
        name: "knowbase_aside_search",
        methods: 'GET',
    )]
    // Also served to the helpdesk FAQ aside, which lists the same articles.
    #[SecurityStrategy(Firewall::STRATEGY_FAQ_ACCESS)]
    public function __invoke(Request $request): Response
    {
        // If we can't see the knowbase, it make no sense to search inside it
        if (!KnowbaseItem::canView()) {
            throw new AccessDeniedHttpException();
        }

        // Get requester filter
        $contains = trim($request->query->getString('contains'));
        if ($contains === '') {
            // An empty filter make no sense
            throw new BadRequestHttpException();
        }

        // Validate offset
        $offset = $request->query->getInt('offset');
        if ($offset < 0 || $offset % SearchResultsBuilder::PAGE_SIZE !== 0) {
            throw new BadRequestHttpException();
        }

        $builder = new SearchResultsBuilder($request->query->getInt('current_id'));

        return $this->render('pages/tools/kb/aside_search_results.html.twig', [
            'results'  => $builder->build($contains, $offset),
            'contains' => $contains,
        ]);
    }
}

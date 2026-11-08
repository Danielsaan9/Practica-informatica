<?php

namespace Glpi\Controller\UI\Illustration;

use Glpi\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route(
        "/UI/Illustration/Search",
        name: "glpi_ui_illustration_search",
    )]
    public function __invoke(Request $request): Response
    {
        // Read parameters
        $filter      = $request->query->getString('filter', "");
        $page        = $request->query->getInt('page', 1);
        $page_size   = $request->query->getInt('page_size', 30);
        $allow_empty = $request->query->getBoolean('allow_empty', false);

        // Output modal body
        return $this->render(
            'components/illustration/icon_picker_search_results.html.twig',
            [
                'filter'      => $filter,
                'page'        => $page,
                'page_size'   => $page_size,
                'allow_empty' => $allow_empty,
            ]
        );
    }
}

<?php

namespace Glpi\Controller\UI\Illustration;

use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Glpi\UI\IllustrationManager;
use Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CustomIllustrationController extends AbstractController
{
    private const CACHE_MAX_AGE = 60 * 60 * 24 * 365;

    public function __construct(
        private IllustrationManager $illustration_manager
    ) {}

    #[SecurityStrategy(Firewall::STRATEGY_NO_CHECK)]
    #[Route(
        "/UI/Illustration/CustomIllustration/{id}",
        name: "glpi_ui_illustration_custom_illustration",
        methods: "GET",
    )]
    public function __invoke(string $id, Request $request): Response
    {
        global $CFG_GLPI;

        // The public FAQ shows custom illustrations to anonymous visitors.
        if (!$CFG_GLPI['use_public_faq']) {
            Session::checkLoginUser();
        }

        $file = $this->illustration_manager->getCustomIllustrationFile($id);
        if (!$file) {
            throw new BadRequestHttpException();
        }

        // Clear the no-cache headers sent by the session cache limiter, Symfony only appends its own.
        header_remove('Cache-Control');
        header_remove('Expires');
        header_remove('Pragma');

        // Id is immutable per upload (see UploadController), safe to cache long-term.
        $response = new BinaryFileResponse($file);
        $response->setAutoEtag();
        $response->setAutoLastModified();
        $response->setCache([
            'private' => true,
            'immutable' => true,
            'max_age' => self::CACHE_MAX_AGE,
        ]);

        // Turns the response into a 304 when the client's cached ETag/Last-Modified still matches.
        $response->isNotModified($request);

        return $response;
    }
}

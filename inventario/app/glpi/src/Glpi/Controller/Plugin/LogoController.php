<?php

namespace Glpi\Controller\Plugin;

use Document;
use Glpi\Controller\AbstractController;
use Glpi\Http\Firewall;
use Glpi\Security\Attribute\SecurityStrategy;
use Plugin;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Safe\base64_decode;

final class LogoController extends AbstractController
{
    /**
     * Base64 encoded transparent 1x1 PNG.
     */
    private const EMPTY_PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=';

    #[SecurityStrategy(Firewall::STRATEGY_ADMIN_ACCESS)]
    #[Route(
        "/Plugin/{plugin_key}/Logo",
        name: "glpi_plugin_logo",
        methods: "GET"
    )]
    public function __invoke(string $plugin_key): Response
    {
        // Try to serve local logo file.
        $plugin_path = Plugin::getPhpDir($plugin_key);
        $logo = \sprintf('%s/logo.png', $plugin_path);

        if (Document::isImage($logo)) {
            return new BinaryFileResponse($logo);
        }

        // Fallback to an empty PNG to prevent 500 error that would pollute logs.
        $empty_png = base64_decode(self::EMPTY_PNG);
        return new Response(
            $empty_png,
            status: 404,
            headers: [
                'Content-Type' => 'image/png',
            ]
        );
    }
}

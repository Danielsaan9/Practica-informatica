<?php

namespace Glpi\Controller;

use Glpi\Application\View\TemplateRenderer;
use Glpi\DependencyInjection\PublicService;
use Glpi\Exception\Http\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController implements PublicService
{
    /**
     * Helper method to get a response containing the content of a rendered
     * twig template.
     *
     * @param string $view Path to a twig template, which will be looked for in
     * the "templates" folder.
     * For example, "my_template.html.twig" will be resolved to `templates/my_template.html.twig`.
     * For plugins, you must use the "@my_plugin_name" prefix.
     * For example, "@formcreator/my_template.html.twig will resolve to
     * `(plugins|marketplace)/formcreator/templates/my_template.html.twig`.
     * @param array $parameters The expected parameters of the twig template.
     * @param Response $response Optional parameter which serves as the "base"
     * response into which the renderer twig content will be inserted.
     * You should only use it if you need to set some specific headers into the
     * response or to set an http return code different than 200.
     *
     * @return Response
     */
    final protected function render(
        string $view,
        array $parameters = [],
        Response $response = new Response(),
    ): Response {
        $twig = TemplateRenderer::getInstance();

        $content = $twig->render($view, $parameters);

        $response->setContent($content);
        return $response;
    }

    /**
     * @param array<mixed> $input
     * @param array<mixed> $keys
     */
    final protected function validateInputHasExactKeys(
        array $input,
        array $keys
    ): void {
        $input_keys = array_keys($input);
        sort($input_keys);
        sort($keys);
        if ($input_keys !== $keys) {
            throw new BadRequestHttpException();
        }
    }
}
